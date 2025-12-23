<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class StrukturOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'struktur_organisasis';

    protected $fillable = [
        'teacher_id',
        'nama',
        'jabatan_struktural',
        'photo',
        'level',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Teacher
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Scope untuk data aktif
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk urutan
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('level', 'asc')->orderBy('urutan', 'asc');
    }

    /**
     * Get nama untuk ditampilkan (dari teacher atau nama manual)
     */
    public function getNamaDisplayAttribute(): string
    {
        if ($this->teacher) {
            return $this->teacher->nama_lengkap;
        }
        return $this->nama ?? '-';
    }

    /**
     * Get photo untuk ditampilkan (dari teacher atau photo manual)
     */
    public function getPhotoDisplayAttribute(): ?string
    {
        if ($this->teacher && $this->teacher->photo) {
            return $this->teacher->photo;
        }
        return $this->photo;
    }

    /**
     * Get inisial untuk avatar
     */
    public function getInitialsAttribute(): string
    {
        $name = $this->nama_display;
        $words = explode(' ', $name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    /**
     * Get kelas/rombel info untuk wali kelas
     */
    public function getKelasRombelAttribute(): ?string
    {
        if ($this->teacher) {
            $rombel = $this->teacher->rombelWaliKelas;
            if ($rombel) {
                return $rombel->kelas?->nama . ' - ' . $rombel->nama;
            }
        }
        return null;
    }

    /**
     * Get jabatan untuk ditampilkan (dengan info kelas untuk wali kelas)
     */
    public function getJabatanDisplayAttribute(): string
    {
        if ($this->jabatan_struktural === 'Wali Kelas' && $this->kelas_rombel) {
            return 'Wali Kelas ' . $this->kelas_rombel;
        }
        return $this->jabatan_struktural;
    }

    /**
     * Get badge color classes based on jabatan
     */
    public function getBadgeColorAttribute(): string
    {
        return match ($this->jabatan_struktural) {
            'Ketua Yayasan' => 'text-amber-400 bg-amber-500/20 border border-amber-500/30',
            'Kepala Madrasah' => 'text-emerald-400 bg-emerald-500/20 border border-emerald-500/30',
            'Wakil Kepala Madrasah' => 'text-teal-400 bg-teal-500/20 border border-teal-500/30',
            'Ketua Komite' => 'text-blue-400 bg-blue-500/20 border border-blue-500/30',
            'Kepala TU', 'Staff TU' => 'text-indigo-400 bg-indigo-500/20 border border-indigo-500/30',
            'Waka Kurikulum' => 'text-cyan-400 bg-cyan-500/20 border border-cyan-500/30',
            'Waka Kesiswaan' => 'text-sky-400 bg-sky-500/20 border border-sky-500/30',
            'Waka Humas' => 'text-violet-400 bg-violet-500/20 border border-violet-500/30',
            'Waka Sarpras' => 'text-purple-400 bg-purple-500/20 border border-purple-500/30',
            'Wali Kelas' => 'text-rose-400 bg-rose-500/20 border border-rose-500/30',
            'Bendahara' => 'text-yellow-400 bg-yellow-500/20 border border-yellow-500/30',
            'Operator' => 'text-orange-400 bg-orange-500/20 border border-orange-500/30',
            'Koordinator BK' => 'text-pink-400 bg-pink-500/20 border border-pink-500/30',
            'Koordinator Ekstrakurikuler' => 'text-fuchsia-400 bg-fuchsia-500/20 border border-fuchsia-500/30',
            'Pustakawan' => 'text-lime-400 bg-lime-500/20 border border-lime-500/30',
            'Penjaga Sekolah' => 'text-stone-400 bg-stone-500/20 border border-stone-500/30',
            default => 'text-gray-400 bg-gray-500/20 border border-gray-500/30',
        };
    }
}

