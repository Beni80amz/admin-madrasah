<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class SiswaMasuk extends Model
{
    use HasFactory;

    protected $table = 'siswa_masuk';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_DITOLAK => 'Ditolak',
        ];
    }

    protected $fillable = [
        'student_id',
        'nama_lengkap',
        'sekolah_asal',
        'kelas_asal',
        'tanggal_masuk',
        'alasan_pindah',
        'nomor_surat_pindah',
        'status',
        'nomor_surat_penerimaan',
        'kelas_tujuan',
        'verified_at',
        'verified_by',
        'catatan_verifikasi',
        'nik',
        'nisn',
        'gender',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ayah',
        'nama_ibu',
        'nomor_mobile',
        'alamat_domisili',
        'photo',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_lahir' => 'date',
        'verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate nomor surat penerimaan when creating
        static::creating(function ($model) {
            if (empty($model->nomor_surat_penerimaan)) {
                $model->nomor_surat_penerimaan = static::generateNomorSurat();
            }
        });
    }

    /**
     * Generate auto-increment nomor surat penerimaan
     * Format: SP-MASUK/YYYY/XXXX
     */
    public static function generateNomorSurat(): string
    {
        $year = date('Y');
        $prefix = "SP-MASUK/{$year}/";

        // Get last number for this year
        $lastRecord = static::where('nomor_surat_penerimaan', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(nomor_surat_penerimaan, "/", -1) AS UNSIGNED) DESC')
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->nomor_surat_penerimaan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relationship to Student (if linked)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship to verifier user
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'verified_by');
    }

    /**
     * Check if this record is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if this record is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    /**
     * Check if this record is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    /**
     * Approve this siswa masuk and create active student
     */
    public function approve(?string $catatan = null): bool
    {
        return DB::transaction(function () use ($catatan) {
            // Update status
            $this->status = self::STATUS_DISETUJUI;
            $this->verified_at = now();
            $this->verified_by = auth()->id();
            $this->catatan_verifikasi = $catatan;
            $this->save();

            // Create active student
            $student = $this->createStudent();

            // Link student to this record
            $this->student_id = $student->id;
            $this->save();

            return true;
        });
    }

    /**
     * Reject this siswa masuk
     */
    public function reject(?string $catatan = null): bool
    {
        $this->status = self::STATUS_DITOLAK;
        $this->verified_at = now();
        $this->verified_by = auth()->id();
        $this->catatan_verifikasi = $catatan;

        return $this->save();
    }

    /**
     * Create a new active student from this siswa masuk data
     */
    public function createStudent(): Student
    {
        // Get active tahun ajaran
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        return Student::create([
            'photo' => $this->photo,
            'nama_lengkap' => $this->nama_lengkap,
            'nis_lokal' => null, // Will be filled by admin later
            'nisn' => $this->nisn,
            'nik' => $this->nik,
            'gender' => $this->gender,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'kelas' => $this->kelas_tujuan,
            'tahun_ajaran_id' => $tahunAjaran?->id,
            'nama_ibu' => $this->nama_ibu,
            'nama_ayah' => $this->nama_ayah,
            'nomor_mobile' => $this->nomor_mobile,
            'alamat_domisili' => $this->alamat_domisili,
            'is_active' => true,
            'status' => Student::STATUS_AKTIF,
        ]);
    }
}
