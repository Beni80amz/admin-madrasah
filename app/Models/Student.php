<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    const STATUS_AKTIF = 'aktif';
    const STATUS_LULUS = 'lulus';
    const STATUS_MUTASI_KELUAR = 'mutasi_keluar';
    const STATUS_MUTASI_MASUK = 'mutasi_masuk';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_LULUS => 'Lulus',
            self::STATUS_MUTASI_KELUAR => 'Mutasi Keluar',
            self::STATUS_MUTASI_MASUK => 'Mutasi Masuk',
        ];
    }

    /**
     * Get status options for forms (excludes Mutasi Masuk since it goes through Siswa Masuk workflow)
     */
    public static function getFormStatusOptions(): array
    {
        return [
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_LULUS => 'Lulus',
            self::STATUS_MUTASI_KELUAR => 'Mutasi Keluar',
        ];
    }

    protected $fillable = [
        'rdm_id',
        'photo',
        'nama_lengkap',
        'nis_lokal',
        'nisn',
        'nik',
        'gender',
        'tempat_lahir',
        'tanggal_lahir',
        'kelas',
        'tahun_ajaran_id',
        'nama_ibu',
        'nama_ayah',
        'nomor_mobile',
        'nomor_pip',
        'alamat_kk',
        'alamat_domisili',
        'is_active',
        'status',
        'status',
        'user_id',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleted(function (Student $student) {
            // Check by user_id link first
            if ($student->user_id) {
                \App\Models\User::find($student->user_id)?->delete();
            }
            // Fallback: Check by NIS as email/username
            elseif ($student->nis_lokal) {
                \App\Models\User::where('email', $student->nis_lokal)->delete();
            }
        });
    }

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }
}

