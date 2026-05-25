<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\LogsActivity;

class Contract extends Model
{
    use HasFactory, LogsActivity;

    protected static function booted(): void
    {
        // Auto-set status based on end_date when creating or updating
        static::creating(function (Contract $contract) {
            $contract->status = Carbon::parse($contract->end_date)->lt(now()->startOfDay()) ? 'expired' : 'active';
        });

        static::updating(function (Contract $contract) {
            if ($contract->isDirty('end_date')) {
                $contract->status = Carbon::parse($contract->end_date)->lt(now()->startOfDay()) ? 'expired' : 'active';
            }
        });
    }

    protected $fillable = [
        'tenant_id',
        'contract_type',
        'no_bak',
        'date_bak',
        'file_bak',
        'no_pks',
        'date_pks',
        'file_pks',
        'start_date',
        'end_date',
        'total_rental_value',
        'security_deposit',
        'sharing_type',
        'company_share_pct',
        'tenant_share_pct',
        'payment_type',
        'payment_start_date',
        'payment_interval_value',
        'payment_interval_unit',
        'status',
        'pihak_pertama',
        'pihak_kedua',
        'renewal_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'date_bak' => 'date',
        'date_pks' => 'date',
        'payment_start_date' => 'date',
        'total_rental_value' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'company_share_pct' => 'decimal:2',
        'tenant_share_pct' => 'decimal:2',
        'payment_type' => 'string',
    ];

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope: hanya kontrak sewa (fixed rental).
     */
    public function scopeSewa($query)
    {
        return $query->where('contract_type', 'sewa');
    }

    /**
     * Scope: hanya kontrak KSU (bagi hasil).
     */
    public function scopeKsu($query)
    {
        return $query->where('contract_type', 'ksu');
    }

    // ==========================================
    // HELPERS
    // ==========================================

    /**
     * Check if this is a KSU (profit-sharing) contract.
     */
    public function isKsu(): bool
    {
        return $this->contract_type === 'ksu';
    }

    /**
     * Check if this is a sewa (fixed rental) contract.
     */
    public function isSewa(): bool
    {
        return $this->contract_type === 'sewa';
    }

    /**
     * Get the tenant that owns this contract.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the workflow for this contract.
     */
    public function workflow(): HasOne
    {
        return $this->hasOne(ContractWorkflow::class);
    }

    /**
     * Get the assets included in this contract.
     */
    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'contract_assets')
                    ->withPivot('rented_area_sqm')
                    ->withTimestamps();
    }

    /**
     * Get all amendments for this contract.
     */
    public function amendments(): HasMany
    {
        return $this->hasMany(\App\Models\Amendment::class);
    }

    /**
     * Get all payments for this contract.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get overdue payments for this contract.
     */
    public function overduePayments(): HasMany
    {
        return $this->hasMany(Payment::class)->where('payment_status', 'overdue');
    }

    /**
     * Get pending payments for this contract.
     */
    public function pendingPayments(): HasMany
    {
        return $this->hasMany(Payment::class)->where('payment_status', 'pending');
    }

    /**
     * Calculate remaining days until contract ends.
     */
    public function getRemainingDaysAttribute(): int
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }

    /**
     * Check if contract is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date < now();
    }

    /**
     * Get number of days since contract expired.
     * Returns 0 if contract is not expired yet.
     */
    public function getDaysExpiredAttribute(): int
    {
        if (!$this->is_expired) {
            return 0;
        }
        return (int) $this->end_date->diffInDays(now());
    }

    /**
     * Generate payment schedule when contract is created.
     * 
     * Logika:
     * - payment_type = 'upfront'  → 1 record, bayar 100% dimuka
     * - payment_type = 'interval' → hitung periode berdasarkan interval
     * - payment_type = 'termin'   → N record sesuai input user (array of {due_date, amount_due})
     * 
     * @param array $termins  Untuk mode termin: [{due_date, amount_due}, ...]
     */
    public function generatePaymentSchedule(array $termins = []): void
    {
        // KSU contracts don't have payment schedules
        // Cash basis dicatat melalui Invoice manual dari hasil rekon
        if ($this->isKsu()) {
            return;
        }

        // Hapus payments lama jika ada (untuk regenerate)
        $this->payments()->forceDelete();

        $paymentStartDate = $this->payment_start_date ?? $this->start_date;
        $today = now()->startOfDay();

        // === UPFRONT ===
        if ($this->payment_type === 'upfront') {
            $dueDate = $paymentStartDate;
            $this->payments()->create([
                'period_number' => 0,
                'due_date' => $dueDate,
                'amount_due' => $this->total_rental_value,
                'amount_paid' => 0,
                'payment_status' => $dueDate < $today ? 'overdue' : 'pending',
            ]);
            return;
        }

        // === TERMIN ===
        if ($this->payment_type === 'termin') {
            foreach ($termins as $i => $termin) {
                $dueDate = Carbon::parse($termin['due_date']);
                $this->payments()->create([
                    'period_number' => $i + 1,
                    'due_date' => $dueDate,
                    'amount_due' => $termin['amount_due'],
                    'amount_paid' => 0,
                    'payment_status' => $dueDate < $today ? 'overdue' : 'pending',
                ]);
            }
            return;
        }

        // === INTERVAL (default) ===
        $startDate = Carbon::parse($paymentStartDate);
        $endDate = Carbon::parse($this->end_date);

        $intervalMonths = $this->payment_interval_unit === 'year'
            ? $this->payment_interval_value * 12
            : $this->payment_interval_value;

        $totalMonths = $startDate->diffInMonths($endDate);
        $periodCount = max(1, (int) ceil($totalMonths / $intervalMonths));
        $amountPerPeriod = round($this->total_rental_value / $periodCount, 2);
        $totalAllocated = 0;

        for ($i = 0; $i < $periodCount; $i++) {
            $dueDate = $startDate->copy()->addMonths($i * $intervalMonths);
            $amount = ($i === $periodCount - 1)
                ? $this->total_rental_value - $totalAllocated
                : $amountPerPeriod;
            $totalAllocated += $amount;
            $status = $dueDate < $today ? 'overdue' : 'pending';

            $this->payments()->create([
                'period_number' => $i + 1,
                'due_date' => $dueDate,
                'amount_due' => $amount,
                'amount_paid' => 0,
                'payment_status' => $status,
            ]);
        }
    }

    /**
     * Get payment schedule summary.
     */
    public function getPaymentSummary(): array
    {
        $payments = $this->payments;
        
        return [
            'total_periods' => $payments->count(),
            'total_due' => $payments->sum('amount_due'),
            'total_paid' => $payments->sum('amount_paid'),
            'pending_count' => $payments->where('payment_status', 'pending')->count(),
            'paid_count' => $payments->where('payment_status', 'paid')->count(),
            'overdue_count' => $payments->where('payment_status', 'overdue')->count(),
            'partial_count' => $payments->where('payment_status', 'partial')->count(),
        ];
    }

    /**
     * Override activity log description.
     */
    protected static function buildDescription($model, string $action): string
    {
        $contractName = $model->no_pks ?? $model->no_bak ?? "ID #{$model->id}";

        if ($action === 'created') {
            return "Membuat contract \"{$contractName}\"";
        }

        if ($action === 'deleted') {
            return "Menghapus contract \"{$contractName}\"";
        }

        if ($action === 'updated') {
            $changes = $model->getChanges();
            $original = $model->getOriginal();
            unset($changes['updated_at']);

            $descriptions = [];
            
            foreach ($changes as $key => $newValue) {
                if ($key === 'file_pks') {
                    $descriptions[] = "memperbarui file PKS";
                    continue;
                }
                if ($key === 'file_bak') {
                    $descriptions[] = "memperbarui file BAK";
                    continue;
                }

                $oldValue = $original[$key] ?? 'kosong';
                if ($oldValue === '') $oldValue = 'kosong';
                
                $newValueStr = $newValue ?? 'kosong';
                if ($newValueStr === '') $newValueStr = 'kosong';
                
                $descriptions[] = "\"{$key}\" dari \"{$oldValue}\" menjadi \"{$newValueStr}\"";
            }
            
            // Menambahkan log perubahan asset jika dilempar dari controller
            if (isset($model->asset_changes_for_log) && is_array($model->asset_changes_for_log)) {
                $descriptions = array_merge($descriptions, $model->asset_changes_for_log);
            }

            if (empty($descriptions)) {
                return "Mengubah data contract \"{$contractName}\"";
            }
            
            $changesString = implode(', ', $descriptions);
            return "Mengubah {$changesString} pada contract \"{$contractName}\"";
        }

        return "{$action} contract \"{$contractName}\"";
    }
}
