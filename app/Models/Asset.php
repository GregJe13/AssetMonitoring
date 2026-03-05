<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_gedung',
        'name',
        'area_sqm',
        'building_condition',
    ];

    protected $casts = [
        'area_sqm' => 'decimal:2',
    ];

    /**
     * Get all contracts that include this asset.
     */
    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class, 'contract_assets')
                    ->withPivot('rented_area_sqm')
                    ->withTimestamps();
    }

    /**
     * Get all amendments that include this asset.
     */
    public function amendments(): BelongsToMany
    {
        return $this->belongsToMany(Amendment::class, 'amendment_assets')
                    ->withPivot('rented_area_sqm')
                    ->withTimestamps();
    }

    /**
     * Check if this asset is currently rented (has active contract).
     */
    public function isCurrentlyRented(): bool
    {
        return $this->contracts()
                    ->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->exists();
    }

    /**
     * Get the current tenant renting this asset.
     */
    public function currentTenant(): ?Tenant
    {
        $activeContract = $this->contracts()
                               ->where('status', 'active')
                               ->where('start_date', '<=', now())
                               ->where('end_date', '>=', now())
                               ->first();
        
        return $activeContract?->tenant;
    }

    /**
     * Get total rented area from all TRULY active contracts.
     * Contract must have status='active' AND start_date <= today AND end_date >= today
     */
    public function getRentedAreaAttribute(): float
    {
        $today = now()->startOfDay();

        // Area from active contracts
        $contractArea = (float) $this->contracts()
            ->where('status', 'active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->sum('contract_assets.rented_area_sqm');

        // Area from active amendments
        $amendmentArea = (float) $this->amendments()
            ->where('status', 'active')
            ->where('new_start_date', '<=', $today)
            ->where('new_end_date', '>=', $today)
            ->sum('amendment_assets.rented_area_sqm');

        return $contractArea + $amendmentArea;
    }

    /**
     * Get available (unrented) area.
     */
    public function getAvailableAreaAttribute(): float
    {
        return max(0, (float) $this->area_sqm - $this->rented_area);
    }

    /**
     * Check if this asset is fully rented (no available space).
     */
    public function isFullyRented(): bool
    {
        return $this->available_area <= 0;
    }

    /**
     * Get all TRULY active contracts for this asset.
     * Contract must have status='active' AND start_date <= today AND end_date >= today
     */
    public function activeContracts()
    {
        return $this->contracts()
            ->where('status', 'active')
            ->where('start_date', '<=', now()->startOfDay())
            ->where('end_date', '>=', now()->startOfDay());
    }

    /**
     * Get active amendments for this asset.
     */
    public function activeAmendments()
    {
        return $this->amendments()
            ->where('status', 'active')
            ->where('new_start_date', '<=', now()->startOfDay())
            ->where('new_end_date', '>=', now()->startOfDay());
    }

    /**
     * Get available area for a specific date range.
     * Checks all non-terminated contracts AND amendments that overlap with the given period.
     */
    public function getAvailableAreaForPeriod($startDate, $endDate, $excludeContractId = null): float
    {
        // Overlapping area from contracts
        $contractRented = $this->contracts()
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->where('status', '!=', 'terminated')
            ->when($excludeContractId, fn($q) => $q->where('contracts.id', '!=', $excludeContractId))
            ->sum('contract_assets.rented_area_sqm');

        // Overlapping area from amendments
        $amendmentRented = $this->amendments()
            ->where('new_start_date', '<=', $endDate)
            ->where('new_end_date', '>=', $startDate)
            ->where('status', '!=', 'expired')
            ->sum('amendment_assets.rented_area_sqm');

        return max(0, (float) $this->area_sqm - $contractRented - $amendmentRented);
    }
}
