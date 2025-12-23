<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPelajaran extends Model
{
    use HasFactory;

    const SEMESTER_GANJIL = 'ganjil';
    const SEMESTER_GENAP = 'genap';

    const HARI_SENIN = 'Senin';
    const HARI_SELASA = 'Selasa';
    const HARI_RABU = 'Rabu';
    const HARI_KAMIS = 'Kamis';
    const HARI_JUMAT = 'Jumat';
    const HARI_SABTU = 'Sabtu';

    protected $fillable = [
        'tahun_ajaran_id',
        'rombel_id',
        'mata_pelajaran_id',
        'teacher_id',
        'semester',
        'hari',
        'jam_ke',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getSemesterOptions(): array
    {
        return [
            self::SEMESTER_GANJIL => 'Ganjil',
            self::SEMESTER_GENAP => 'Genap',
        ];
    }

    public static function getHariOptions(): array
    {
        return [
            self::HARI_SENIN => 'Senin',
            self::HARI_SELASA => 'Selasa',
            self::HARI_RABU => 'Rabu',
            self::HARI_KAMIS => 'Kamis',
            self::HARI_JUMAT => 'Jumat',
            self::HARI_SABTU => 'Sabtu',
        ];
    }

    public static function getJamKeOptions(): array
    {
        return [
            1 => 'Jam ke-1',
            2 => 'Jam ke-2',
            3 => 'Jam ke-3',
            4 => 'Jam ke-4',
            5 => 'Jam ke-5',
            6 => 'Jam ke-6',
            7 => 'Jam ke-7',
            8 => 'Jam ke-8',
        ];
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Check if teacher has conflict at the same time
     */
    public static function hasTeacherConflict(
        int $teacherId,
        int $tahunAjaranId,
        string $semester,
        string $hari,
        int $jamKe,
        ?int $excludeId = null
    ): bool {
        $query = static::where('teacher_id', $teacherId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('semester', $semester)
            ->where('hari', $hari)
            ->where('jam_ke', $jamKe);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get teacher's conflicting schedule
     */
    public static function getTeacherConflict(
        int $teacherId,
        int $tahunAjaranId,
        string $semester,
        string $hari,
        int $jamKe,
        ?int $excludeId = null
    ): ?self {
        $query = static::with(['rombel.kelas', 'mataPelajaran'])
            ->where('teacher_id', $teacherId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('semester', $semester)
            ->where('hari', $hari)
            ->where('jam_ke', $jamKe);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->first();
    }
}
