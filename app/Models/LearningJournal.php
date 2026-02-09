<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mata_pelajaran_id',
        'rombel_id',
        'date',
        'pertemuan_ke',
        'materi',
        'absensi_s',
        'absensi_i',
        'absensi_a',
        'hambatan',
        'solusi',
        'students_sakit',
        'students_izin',
        'students_alpha',
    ];

    protected $casts = [
        'date' => 'date',
        'absensi_s' => 'integer',
        'absensi_i' => 'integer',
        'absensi_a' => 'integer',
        'students_sakit' => 'array',
        'students_izin' => 'array',
        'students_alpha' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }
}
