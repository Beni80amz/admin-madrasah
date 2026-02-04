<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function feeCategory(): BelongsTo
    {
        return $this->belongsTo(FeeCategory::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function studentBills(): HasMany
    {
        return $this->hasMany(StudentBill::class);
    }

    /**
     * Scope for active items only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for monthly fees
     */
    public function scopeMonthly($query)
    {
        return $query->where('frequency', 'monthly');
    }

    /**
     * Scope for one-time fees
     */
    public function scopeOnce($query)
    {
        return $query->where('frequency', 'once');
    }

    /**
     * Check if this is a monthly fee
     */
    public function isMonthly(): bool
    {
        return $this->frequency === 'monthly';
    }

    /**
     * Get display name with category
     */
    public function getFullNameAttribute(): string
    {
        return $this->feeCategory?->name . ' - ' . $this->name;
    }
}
