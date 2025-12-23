<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'image',
        'video_url',
        'category',
        'description',
        'is_featured',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope: only active items
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: ordered by urutan
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Scope: only photos
     */
    public function scopePhotos(Builder $query): Builder
    {
        return $query->where('type', 'photo');
    }

    /**
     * Scope: only videos
     */
    public function scopeVideos(Builder $query): Builder
    {
        return $query->where('type', 'video');
    }

    /**
     * Scope: featured items
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}

