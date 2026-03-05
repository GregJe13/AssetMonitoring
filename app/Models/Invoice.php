<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'description',
        'amount',
        'tenant_id',
        'tenant_name_manual',
        'invoice_date',
        'due_date',
        'status',
        'paid_at',
        'file_path',
        'file_original_name',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
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

    /**
     * Get status badge color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'bg-green-50 text-green-700 ring-green-600/20',
            'unpaid' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
            default => 'bg-gray-50 text-gray-700 ring-gray-600/20',
        };
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }
}
