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
        'rdm_id',
        'photo',
        'nama_lengkap',
        'nip',
        'nuptk',
        'npk_peg_id',
        'jabatan_id',
        'tugas_pokok_id',
        'tugas_tambahan_id',
        'mata_pelajaran_id',
        'kelas_id',
        'rombel_id',
        'status',
        'sertifikasi',
        'is_active',
        'is_active',
        'user_id',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleted(function (Teacher $teacher) {
            // Check by user_id link first
            if ($teacher->user_id) {
                \App\Models\User::find($teacher->user_id)?->delete();
            }
            // Fallback: Check by NIP as email (common pattern)
            elseif ($teacher->nip) {
                \App\Models\User::where('email', $teacher->nip)->delete();
            }
        });
    }

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
    public function ensureUserExists(): void
    {
        try {
            // If user already linked, skip
            if ($this->user_id) {
                return;
            }

            // Determine password and email
            $password = $this->nip ?? $this->nik ?? '12345678';
            $email = $this->nip ? $this->nip . '@teacher.com' : ($this->nik ? $this->nik . '@teacher.com' : uniqid() . '@teacher.com');

            // Check if user already exists
            $existingUser = \App\Models\User::where('email', $email)->first();

            if (!$existingUser) {
                // Ensure role exists
                if (!\Spatie\Permission\Models\Role::where('name', 'teacher')->exists()) {
                    \Spatie\Permission\Models\Role::create(['name' => 'teacher', 'guard_name' => 'web']);
                }

                $user = \App\Models\User::create([
                    'name' => $this->nama_lengkap,
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                    'email_verified_at' => now(),
                ]);

                // Assign role
                $user->assignRole('teacher');

                // Link user to teacher
                $this->user_id = $user->id;
                $this->save();

            } else {
                // If user exists, try to link it
                $this->user_id = $existingUser->id;
                $this->save();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Auto Create User Error: ' . $e->getMessage());
        }
    }
}
