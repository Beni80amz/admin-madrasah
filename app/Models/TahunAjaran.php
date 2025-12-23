<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'hari_efektif_ganjil',
        'hari_efektif_genap',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    public function rombels(): HasMany
    {
        return $this->hasMany(Rombel::class);
    }

    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }
}
