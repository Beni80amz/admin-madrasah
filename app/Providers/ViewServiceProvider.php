<?php

namespace App\Providers;

use App\Models\AppSetting;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share siteProfile to ALL views
        // Use firstOrNew to prevent "Attempt to read property on null" if table is empty
        View::share('siteProfile', ProfileMadrasah::firstOrNew());

        // Share active tahunAjaran to ALL views
        View::share('tahunAjaran', TahunAjaran::getActive());

        // Share theme mode to ALL views (with safety check for migrations)
        if (Schema::hasTable('app_settings')) {
            View::share('themeMode', AppSetting::getThemeMode());
        } else {
            View::share('themeMode', 'dark');
        }
    }
}
