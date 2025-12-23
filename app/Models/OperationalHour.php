<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalHour extends Model
{
    protected $fillable = [
        'hari',
        'waktu',
        'is_libur',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_libur' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk mengambil hanya yang aktif dan terurut
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }
}
