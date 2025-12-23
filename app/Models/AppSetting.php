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
}
