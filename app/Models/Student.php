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
        'no_kk',
        'nama_kepala_keluarga_diKK',
        'gender',
        'tempat_lahir',
        'tanggal_lahir',
        'kelas',
        'tahun_ajaran_id',
        'nama_ibu',
        'status_ibu',
        'nik_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'pekerjaan_ibu_lainnya',
        'nama_ayah',
        'status_ayah',
        'nik_ayah_kandung',
        'tempat_lahir_ayah_kandung',
        'tgl_lahir_ayah_kandung',
        'pendidikan_ayah_kandung',
        'pekerjaan_ayah_kandung',
        'pekerjaan_ayah_kandung_lainnya',
        'nik_wali',
        'tempat_lahir_wali',
        'tanggal_lahir_wali',
        'pendidikan_wali',
        'pekerjaan_wali',
        'pekerjaan_wali_lainnya',
        'nomor_mobile',
        'nomor_pip',
        'alamat_kk',
        'alamat_domisili',
        'penghasilan_orangtua',
        'status_rumah',
        'status_rumah_lainnya',
        'is_active',
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
        'tgl_lahir_ayah_kandung' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'tanggal_lahir_wali' => 'date',
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

