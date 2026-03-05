<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_tenant',
        'phone',
        'email',
        'npwp',
        'pic',
        'pic_phone',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get ACTIVE contracts only (default untuk tampilan).
     * Gunakan ini untuk menampilkan kontrak yang sedang berjalan.
     * Must have status='active' AND end_date has not passed.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class)
                    ->where('status', 'active')
                    ->where('end_date', '>=', now()->toDateString());
    }

    /**
     * Get ALL contracts including expired (untuk history/log).
     * Gunakan ini untuk melihat semua riwayat kontrak.
     */
    public function contractHistory(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get only EXPIRED contracts.
     * Includes contracts with status='expired' OR where end_date has passed.
     */
    public function expiredContracts(): HasMany
    {
        return $this->hasMany(Contract::class)
                    ->where(function($query) {
                        $query->where('status', 'expired')
                              ->orWhere('end_date', '<', now()->toDateString());
                    });
    }

    /**
     * Get all payments across all contracts for this tenant.
     */
    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Contract::class);
    }

    // ==========================================
    // PARTNERSHIP STATISTICS (untuk log/history)
    // ==========================================

    /**
     * Total durasi kerjasama dalam hari.
     */
    public function getTotalPartnershipDaysAttribute(): int
    {
        return $this->contractHistory()
            ->selectRaw('SUM(DATEDIFF(end_date, start_date)) as total_days')
            ->value('total_days') ?? 0;
    }

    /**
     * Total durasi kerjasama dalam tahun.
     */
    public function getTotalPartnershipYearsAttribute(): float
    {
        return round($this->total_partnership_days / 365, 1);
    }

    /**
     * Tanggal kontrak pertama (kapan pertama kali bekerjasama).
     */
    public function getFirstContractDateAttribute(): ?Carbon
    {
        $date = $this->contractHistory()->min('start_date');
        return $date ? Carbon::parse($date) : null;
    }

    /**
     * Jumlah total kontrak (termasuk expired).
     */
    public function getTotalContractsAttribute(): int
    {
        return $this->contractHistory()->count();
    }

    /**
     * Jumlah kontrak yang sudah selesai/expired.
     */
    public function getCompletedContractsAttribute(): int
    {
        return $this->expiredContracts()->count();
    }

    /**
     * Total nilai sewa sepanjang kerjasama.
     */
    public function getTotalRentalValueAttribute(): float
    {
        return $this->contractHistory()->sum('total_rental_value') ?? 0;
    }

    /**
     * Get partnership summary for this tenant.
     */
    public function getPartnershipSummary(): array
    {
        return [
            'first_contract_date' => $this->first_contract_date?->format('Y-m-d'),
            'total_contracts' => $this->total_contracts,
            'active_contracts' => $this->contracts()->count(),
            'completed_contracts' => $this->completed_contracts,
            'total_partnership_days' => $this->total_partnership_days,
            'total_partnership_years' => $this->total_partnership_years,
            'total_rental_value' => $this->total_rental_value,
        ];
    }
}
