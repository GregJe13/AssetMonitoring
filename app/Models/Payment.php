<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class Payment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'contract_id',
        'amendment_id',
        'period_number',
        'due_date',
        'paid_at',
        'amount_due',
        'amount_paid',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'date',
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Get the contract that this payment belongs to.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the amendment that this payment belongs to (if any).
     */
    public function amendment(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Amendment::class);
    }

    /**
     * Get the tenant through the contract.
     */
    public function tenant()
    {
        return $this->hasOneThrough(
            Tenant::class,
            Contract::class,
            'id',         // contracts.id
            'id',         // tenants.id
            'contract_id', // payments.contract_id
            'tenant_id'   // contracts.tenant_id
        );
    }

    /**
     * Check if payment is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date < now() &&
            in_array($this->payment_status, ['pending', 'partial']);
    }

    /**
     * Get days until due date (negative if overdue).
     */
    public function getDaysUntilDueAttribute(): int
    {
        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Get outstanding amount (amount_due - amount_paid).
     */
    public function getOutstandingAmountAttribute(): float
    {
        return max(0, $this->amount_due - $this->amount_paid);
    }

    /**
     * Mark payment as paid.
     */
    public function markAsPaid(?float $amountPaid = null, ?Carbon $paidAt = null): void
    {
        $amountPaid = $amountPaid ?? $this->amount_due;
        $paidAt = $paidAt ?? now();

        $this->update([
            'amount_paid' => $amountPaid,
            'paid_at' => $paidAt,
            'payment_status' => $amountPaid >= $this->amount_due ? 'paid' : 'partial',
        ]);
    }

    /**
     * Mark payment as overdue (called by scheduled command).
     */
    public function markAsOverdue(): void
    {
        if ($this->payment_status === 'pending' && $this->due_date < now()) {
            $this->update(['payment_status' => 'overdue']);
        }
    }

    /**
     * Scope: Get overdue payments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue');
    }

    /**
     * Scope: Get pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope: Get payments due within N days.
     */
    public function scopeDueWithinDays($query, int $days)
    {
        return $query->where('payment_status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays($days)]);
    }

    /**
     * Scope: Get payments that should be marked as overdue.
     */
    public function scopeShouldBeOverdue($query)
    {
        return $query->where('payment_status', 'pending')
            ->where('due_date', '<', now());
    }

    /**
     * Override activity log description.
     */
    protected static function buildDescription($model, string $action): string
    {
        $model->loadMissing(['contract.tenant']);
        $tenantName = $model->contract->tenant->name ?? 'Unknown Tenant';
        $period = $model->period_number;

        if ($action === 'updated') {
            $changes = $model->getChanges();
            if (isset($changes['payment_status'])) {
                $status = $changes['payment_status'];
                if ($status === 'paid') {
                    return "Menekan tombol mark as paid kontrak yang dimiliki oleh tenant {$tenantName} pada periode ke #{$period}";
                }
                return "Mengubah status pembayaran menjadi {$status} kontrak yang dimiliki oleh tenant {$tenantName} pada periode ke #{$period}";
            }
            return "Mengubah data pembayaran kontrak yang dimiliki oleh tenant {$tenantName} pada periode ke #{$period}";
        }

        if ($action === 'created') {
            return "Membuat jadwal pembayaran baru kontrak yang dimiliki oleh tenant {$tenantName} pada periode ke #{$period}";
        }

        if ($action === 'deleted') {
            return "Menghapus data pembayaran kontrak yang dimiliki oleh tenant {$tenantName} pada periode ke #{$period}";
        }

        return "{$action} pembayaran kontrak yang dimiliki oleh tenant {$tenantName} pada periode ke #{$period}";
    }
}
