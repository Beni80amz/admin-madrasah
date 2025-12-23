<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaKeluar extends Model
{
    use HasFactory;

    protected $table = 'siswa_keluar';

    protected $fillable = [
        'student_id',
        'photo',
        'nama_lengkap',
        'nis_lokal',
        'nisn',
        'nik',
        'gender',
        'kelas_terakhir',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ibu',
        'nama_ayah',
        'nomor_mobile',
        'alamat',
        'tanggal_keluar',
        'alasan_keluar',
        'sekolah_tujuan',
        'nomor_surat',
        'nomor_dokumen_emis',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_keluar' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate nomor surat when creating
        static::creating(function ($model) {
            if (empty($model->nomor_surat)) {
                $model->nomor_surat = static::generateNomorSurat();
            }
        });
    }

    /**
     * Generate auto-increment nomor surat
     * Format: SP-KELUAR/YYYY/XXXX
     */
    public static function generateNomorSurat(): string
    {
        $year = date('Y');
        $prefix = "SP-KELUAR/{$year}/";

        // Get last number for this year
        $lastRecord = static::where('nomor_surat', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(nomor_surat, "/", -1) AS UNSIGNED) DESC')
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->nomor_surat, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
