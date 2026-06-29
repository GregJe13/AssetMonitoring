<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\LogsActivity;

class Invoice extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'invoice_number',
        'description',
        'amount',
        'tenant_id',
        'tenant_name_manual',
        'invoice_date',
        'payment_date',
        'file_path',
        'file_original_name',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'invoice_date' => 'date',
        'payment_date' => 'date',
    ];

    // ── Relationships ──

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'invoice_assets')
                    ->withTimestamps();
    }

    // ── Helpers ──

    /**
     * Get the display name for the tenant.
     * Returns tenant name from DB if linked, otherwise manual name.
     */
    public function getDisplayTenantNameAttribute(): string
    {
        return $this->tenant?->name ?? $this->tenant_name_manual ?? '-';
    }
}
