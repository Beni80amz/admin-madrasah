<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key
     */
    public static function getValue(string $key, $default = null): mixed
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key
     */
    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("app_setting_{$key}");
    }

    /**
     * Get theme mode (dark, light, custom)
     */
    public static function getThemeMode(): string
    {
        return static::getValue('theme_mode', 'dark');
    }

    /**
     * Set theme mode
     */
    public static function setThemeMode(string $mode): void
    {
        static::setValue('theme_mode', $mode);
    }
    /**
     * Check if PPDB feature is active
     */
    public static function isPpdbActive(): bool
    {
        return (bool) static::getValue('ppdb_active', false);
    }

    /**
     * Set PPDB feature status
     */
    public static function setPpdbActive(bool $active): void
    {
        static::setValue('ppdb_active', $active ? '1' : '0');
    }

    /**
     * Get all PPDB information
     */
    public static function getPpdbInfo(): array
    {
        return [
            'active' => static::isPpdbActive(),
            'alur' => static::getPpdbAlur(),
            'period' => static::getPpdbPeriod(),
            'persyaratan' => static::getPpdbPersyaratan(),
            'biaya' => static::getValue('ppdb_biaya', 'Gratis'),
            'kuota' => static::getValue('ppdb_kuota', 100),
            'tahun_ajaran' => static::getValue('ppdb_tahun_ajaran', date('Y') . '/' . (date('Y') + 1)),
        ];
    }

    /**
     * Get PPDB registration flow steps
     */
    public static function getPpdbAlur(): array
    {
        $alur = static::getValue('ppdb_alur', null);
        if ($alur) {
            return json_decode($alur, true) ?? static::getDefaultAlur();
        }
        return static::getDefaultAlur();
    }

    /**
     * Get default PPDB registration flow
     */
    private static function getDefaultAlur(): array
    {
        return [
            ['step' => 1, 'title' => 'Pendaftaran Online', 'description' => 'Isi formulir pendaftaran secara online melalui website'],
            ['step' => 2, 'title' => 'Upload Dokumen', 'description' => 'Unggah dokumen persyaratan (KK, Akta, Foto)'],
            ['step' => 3, 'title' => 'Verifikasi', 'description' => 'Tim PPDB akan memverifikasi data dan dokumen'],
            ['step' => 4, 'title' => 'Pengumuman', 'description' => 'Hasil seleksi akan diumumkan melalui website/WhatsApp'],
            ['step' => 5, 'title' => 'Daftar Ulang', 'description' => 'Calon siswa yang diterima melakukan daftar ulang'],
        ];
    }

    /**
     * Get PPDB registration period
     */
    public static function getPpdbPeriod(): array
    {
        return [
            'start' => static::getValue('ppdb_tanggal_mulai', date('Y') . '-01-01'),
            'end' => static::getValue('ppdb_tanggal_selesai', date('Y') . '-07-31'),
        ];
    }

    /**
     * Get PPDB requirements list
     */
    public static function getPpdbPersyaratan(): array
    {
        $persyaratan = static::getValue('ppdb_persyaratan', null);
        if ($persyaratan) {
            return json_decode($persyaratan, true) ?? static::getDefaultPersyaratan();
        }
        return static::getDefaultPersyaratan();
    }

    /**
     * Get default PPDB requirements
     */
    private static function getDefaultPersyaratan(): array
    {
        return [
            'Fotokopi Kartu Keluarga (KK)',
            'Fotokopi Akta Kelahiran',
            'Fotokopi Ijazah TK/RA/PAUD (jika ada)',
            'Pas Foto Berwarna 3x4 (4 lembar)',
            'Fotokopi KTP Orang Tua/Wali',
        ];
    }

    /**
     * Set PPDB Tahun Ajaran
     */
    public static function setPpdbTahunAjaran(string $value): void
    {
        static::setValue('ppdb_tahun_ajaran', $value);
    }

    /**
     * Set PPDB registration start date
     */
    public static function setPpdbTanggalMulai(string $date): void
    {
        static::setValue('ppdb_tanggal_mulai', $date);
    }

    /**
     * Set PPDB registration end date
     */
    public static function setPpdbTanggalSelesai(string $date): void
    {
        static::setValue('ppdb_tanggal_selesai', $date);
    }

    /**
     * Set PPDB quota
     */
    public static function setPpdbKuota(int $kuota): void
    {
        static::setValue('ppdb_kuota', (string) $kuota);
    }

    /**
     * Set PPDB registration fee
     */
    public static function setPpdbBiaya(string $biaya): void
    {
        static::setValue('ppdb_biaya', $biaya);
    }

    /**
     * Set PPDB registration flow steps
     */
    public static function setPpdbAlur(array $alur): void
    {
        static::setValue('ppdb_alur', json_encode($alur));
    }

    /**
     * Set PPDB requirements list
     */
    public static function setPpdbPersyaratan(array $persyaratan): void
    {
        static::setValue('ppdb_persyaratan', json_encode($persyaratan));
    }
}
