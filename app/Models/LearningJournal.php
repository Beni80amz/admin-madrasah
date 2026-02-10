<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Student;

class LearningJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mata_pelajaran_id',
        'rombel_id',
        'date',
        'semester',
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

    public function getFormattedAttendanceNames(): string
    {
        $sakit = $this->students_sakit ?? [];
        $izin = $this->students_izin ?? [];
        $alpha = $this->students_alpha ?? [];

        $allIds = array_unique(array_merge($sakit, $izin, $alpha));
        if (empty($allIds)) {
            return "0 (S), 0 (I), 0 (A)";
        }

        $students = Student::whereIn('id', $allIds)->get()->keyBy('id');

        $result = [];

        if (!empty($sakit)) {
            foreach ($sakit as $id) {
                if (isset($students[$id])) {
                    $result[] = $students[$id]->nama_lengkap . " (S)";
                }
            }
        }

        if (!empty($izin)) {
            foreach ($izin as $id) {
                if (isset($students[$id])) {
                    $result[] = $students[$id]->nama_lengkap . " (I)";
                }
            }
        }

        if (!empty($alpha)) {
            foreach ($alpha as $id) {
                if (isset($students[$id])) {
                    $result[] = $students[$id]->nama_lengkap . " (A)";
                }
            }
        }

        if (empty($result)) {
            return "0 (S), 0 (I), 0 (A)";
        }

        // Handle the "0 (A)" case if no one is alpha
        if (empty($alpha)) {
            $result[] = "0 (A)";
        }

        return implode(", ", $result);
    }
}
