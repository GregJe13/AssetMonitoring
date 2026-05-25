<?php

namespace App\Observers;

use App\Models\Contract;

class ContractObserver
{
    /**
     * Handle the Contract "created" event.
     * 
     * Otomatis generate jadwal pembayaran saat kontrak baru dibuat.
     */
    public function created(Contract $contract): void
    {
        // Hanya generate payments jika kontrak berstatus active
        if ($contract->status === 'active') {
            $contract->generatePaymentSchedule();
        }
    }

    /**
     * Handle the Contract "updated" event.
     * 
     * Regenerate payments jika ada perubahan yang mempengaruhi jadwal pembayaran.
     */
    public function updated(Contract $contract): void
    {
        // Check jika ada perubahan pada field yang mempengaruhi jadwal
        $paymentAffectingFields = [
            'start_date',
            'end_date', 
            'total_rental_value',
            'payment_type',
            'payment_interval_value',
            'payment_interval_unit',
        ];

        $hasPaymentChanges = false;
        foreach ($paymentAffectingFields as $field) {
            if ($contract->wasChanged($field)) {
                $hasPaymentChanges = true;
                break;
            }
        }

        // Jika status berubah ke active, generate payments
        if ($contract->wasChanged('status') && $contract->status === 'active') {
            $contract->generatePaymentSchedule();
            return;
        }

        // Jika ada perubahan pada jadwal pembayaran dan status active,
        // regenerate payments (hanya yang belum dibayar)
        if ($hasPaymentChanges && $contract->status === 'active') {
            // Hanya hapus payments yang masih pending
            $contract->payments()->where('payment_status', 'pending')->forceDelete();
            
            // Bisa ditambahkan logic untuk regenerate yang pending saja
            // Untuk sekarang, kita regenerate semua
            $contract->generatePaymentSchedule();
        }
    }

    /**
     * Handle the Contract "deleted" event.
     */
    public function deleted(Contract $contract): void
    {
        // Soft delete semua payments terkait
        $contract->payments()->delete();
    }

    /**
     * Handle the Contract "restored" event.
     */
    public function restored(Contract $contract): void
    {
        // Restore semua payments terkait
        $contract->payments()->withTrashed()->restore();
    }

    /**
     * Handle the Contract "force deleted" event.
     */
    public function forceDeleted(Contract $contract): void
    {
        // Cascade delete sudah di-handle oleh database constraint
    }
}
