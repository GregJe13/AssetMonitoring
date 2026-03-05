<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contract extends Model
{
    use HasFactory;

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
        'is_upfront',
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
        'is_upfront' => 'boolean',
    ];

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
     * - Jika is_upfront = true → buat 1 record pembayaran dengan due_date = start_date
     * - Jika is_upfront = false → hitung jumlah periode berdasarkan interval, 
     *   buat record pembayaran untuk setiap periode
     * 
     * Note: Menggunakan payment_start_date jika diisi, jika tidak menggunakan start_date
     */
    public function generatePaymentSchedule(): void
    {
        // Hapus payments lama jika ada (untuk regenerate)
        $this->payments()->forceDelete();

        // Gunakan payment_start_date jika ada, jika tidak gunakan start_date
        $paymentStartDate = $this->payment_start_date ?? $this->start_date;

        if ($this->is_upfront) {
            // Bayar 100% dimuka - hanya 1 record
            $dueDate = $paymentStartDate;
            $this->payments()->create([
                'period_number' => 0,
                'due_date' => $dueDate,
                'amount_due' => $this->total_rental_value,
                'amount_paid' => 0,
                'payment_status' => $dueDate < now()->startOfDay() ? 'overdue' : 'pending',
            ]);
            return;
        }

        // Hitung jumlah periode berdasarkan interval
        $startDate = Carbon::parse($paymentStartDate);
        $endDate = Carbon::parse($this->end_date);
        $today = now()->startOfDay();

        // Konversi interval ke bulan
        $intervalMonths = $this->payment_interval_unit === 'year'
            ? $this->payment_interval_value * 12
            : $this->payment_interval_value;

        // Hitung total bulan kontrak dan jumlah periode
        $totalMonths = $startDate->diffInMonths($endDate);
        $periodCount = max(1, (int) ceil($totalMonths / $intervalMonths));
        
        // Hitung amount per periode
        $amountPerPeriod = round($this->total_rental_value / $periodCount, 2);
        
        // Handle pembulatan agar total tetap akurat
        $totalAllocated = 0;

        // Generate payment records
        for ($i = 0; $i < $periodCount; $i++) {
            $dueDate = $startDate->copy()->addMonths($i * $intervalMonths);
            
            // Untuk periode terakhir, sesuaikan amount agar total pas
            $amount = ($i === $periodCount - 1)
                ? $this->total_rental_value - $totalAllocated
                : $amountPerPeriod;
            
            $totalAllocated += $amount;

            // Set status: overdue jika due_date sudah lewat
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
}
