<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Amendment extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        // Auto-set status based on new_end_date
        static::creating(function (Amendment $amendment) {
            $amendment->status = Carbon::parse($amendment->new_end_date)->lt(now()->startOfDay()) ? 'expired' : 'active';
        });

        static::updating(function (Amendment $amendment) {
            if ($amendment->isDirty('new_end_date')) {
                $amendment->status = Carbon::parse($amendment->new_end_date)->lt(now()->startOfDay()) ? 'expired' : 'active';
            }
        });
    }

    protected $fillable = [
        'contract_id',
        'amendment_number',
        'no_amendment',
        'date_amendment',
        'old_start_date',
        'old_end_date',
        'new_start_date',
        'new_end_date',
        'total_rental_value',
        'payment_type',
        'payment_start_date',
        'payment_interval_value',
        'payment_interval_unit',
        'no_bak',
        'date_bak',
        'file_bak',
        'no_pks',
        'date_pks',
        'file_pks',
        'pihak_pertama',
        'pihak_kedua',
        'notes',
        'status',
    ];

    protected $casts = [
        'date_amendment' => 'date',
        'old_start_date' => 'date',
        'old_end_date' => 'date',
        'new_start_date' => 'date',
        'new_end_date' => 'date',
        'date_bak' => 'date',
        'date_pks' => 'date',
        'payment_start_date' => 'date',
        'total_rental_value' => 'decimal:2',
        'payment_type' => 'string',
    ];

    /**
     * Get the parent contract.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the assets for this amendment.
     */
    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'amendment_assets')
                    ->withPivot('rented_area_sqm')
                    ->withTimestamps();
    }

    /**
     * Get payments for this amendment.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the tenant (via contract).
     */
    public function getTenantAttribute()
    {
        return $this->contract->tenant;
    }

    /**
     * Check if amendment is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->new_end_date < now();
    }

    /**
     * Get remaining days.
     */
    public function getRemainingDaysAttribute(): int
    {
        return max(0, now()->diffInDays($this->new_end_date, false));
    }

    /**
     * Generate payment schedule for this amendment.
     * Supports upfront, interval, and termin modes.
     *
     * @param array $termins  For termin mode: [{due_date, amount_due}, ...]
     */
    public function generatePaymentSchedule(array $termins = []): void
    {
        $this->payments()->delete();

        $paymentStartDate = $this->payment_start_date ?? $this->new_start_date;
        $today = now()->startOfDay();

        // === UPFRONT ===
        if ($this->payment_type === 'upfront') {
            $dueDate = $paymentStartDate;
            $this->payments()->create([
                'contract_id' => $this->contract_id,
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
                    'contract_id' => $this->contract_id,
                    'period_number' => $i + 1,
                    'due_date' => $dueDate,
                    'amount_due' => $termin['amount_due'],
                    'amount_paid' => 0,
                    'payment_status' => $dueDate < $today ? 'overdue' : 'pending',
                ]);
            }
            return;
        }

        // === INTERVAL ===
        $startDate = Carbon::parse($paymentStartDate);
        $endDate = Carbon::parse($this->new_end_date);

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
                'contract_id' => $this->contract_id,
                'period_number' => $i + 1,
                'due_date' => $dueDate,
                'amount_due' => $amount,
                'amount_paid' => 0,
                'payment_status' => $status,
            ]);
        }
    }
}
