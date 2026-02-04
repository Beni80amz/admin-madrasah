<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function feeItems(): HasMany
    {
        return $this->hasMany(FeeItem::class);
    }

    /**
     * Scope for active categories only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
