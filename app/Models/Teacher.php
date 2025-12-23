<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'nama_lengkap',
        'nip',
        'jabatan_id',
        'tugas_pokok_id',
        'tugas_tambahan_id',
        'mata_pelajaran_id',
        'kelas_id',
        'rombel_id',
        'status',
        'sertifikasi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function tugasPokok(): BelongsTo
    {
        return $this->belongsTo(TugasPokok::class);
    }

    public function tugasTambahan(): BelongsTo
    {
        return $this->belongsTo(TugasTambahan::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    /**
     * Get rombel where this teacher is the wali kelas (homeroom teacher)
     */
    public function rombelWaliKelas(): HasOne
    {
        return $this->hasOne(Rombel::class, 'wali_kelas_id');
    }

    /**
     * Get Kelas/Rombel name from the rombel where this teacher is wali kelas
     */
    public function getKelasRombelAttribute(): string
    {
        $rombel = $this->rombelWaliKelas;
        if ($rombel) {
            return $rombel->kelas?->nama . ' / ' . $rombel->nama;
        }
        return '-';
    }
}
