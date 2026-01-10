<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Models\AppSetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Get theme mode from settings (with safety check for migrations)
        $themeMode = 'dark';
        if (Schema::hasTable('app_settings')) {
            $themeMode = AppSetting::getThemeMode();
        }

        // Determine dark mode settings based on theme_mode
        $darkModeEnabled = match ($themeMode) {
            'light' => false,
            'custom' => true, // Allow toggle
            default => true,  // dark mode
        };
        $darkModeForced = $themeMode !== 'custom';

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->spa()
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->font('Inter')
            ->darkMode($darkModeEnabled, $darkModeForced)
            ->brandName('Madrasah Portal')
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn() => Blade::render('
                    <div class="mt-6 pt-6 border-t border-gray-700 text-center">
                        <a href="/" class="text-sm text-primary-500 hover:text-primary-400 font-medium">
                            ← Kembali ke Beranda
                        </a>
                    </div>
                ')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn() => Blade::render('
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <style>
                        /* Sticky columns for student table - Checkbox, Photo, Nama Lengkap */
                        .fi-ta-table {
                            position: relative;
                        }
                        .fi-ta-row > td:nth-child(1),
                        .fi-ta-row > td:nth-child(2),
                        .fi-ta-row > td:nth-child(3),
                        .fi-ta-header-cell:nth-child(1),
                        .fi-ta-header-cell:nth-child(2),
                        .fi-ta-header-cell:nth-child(3) {
                            position: sticky !important;
                            z-index: 10 !important;
                        }
                        /* Position: Checkbox at 0, Photo at 50px, Nama at 120px */
                        .fi-ta-row > td:nth-child(1),
                        .fi-ta-header-cell:nth-child(1) {
                            left: 0 !important;
                        }
                        .fi-ta-row > td:nth-child(2),
                        .fi-ta-header-cell:nth-child(2) {
                            left: 50px !important;
                        }
                        .fi-ta-row > td:nth-child(3),
                        .fi-ta-header-cell:nth-child(3) {
                            left: 120px !important;
                        }
                        .fi-ta-header-cell:nth-child(1),
                        .fi-ta-header-cell:nth-child(2),
                        .fi-ta-header-cell:nth-child(3) {
                            z-index: 20 !important;
                        }
                        /* Light mode background */
                        .fi-ta-row > td:nth-child(1),
                        .fi-ta-row > td:nth-child(2),
                        .fi-ta-row > td:nth-child(3) {
                            background: white !important;
                        }
                        .fi-ta-header-cell:nth-child(1),
                        .fi-ta-header-cell:nth-child(2),
                        .fi-ta-header-cell:nth-child(3) {
                            background: rgb(249 250 251) !important;
                        }
                        /* Dark mode background */
                        .dark .fi-ta-row > td:nth-child(1),
                        .dark .fi-ta-row > td:nth-child(2),
                        .dark .fi-ta-row > td:nth-child(3) {
                            background: rgb(30 41 59) !important;
                        }
                        .dark .fi-ta-header-cell:nth-child(1),
                        .dark .fi-ta-header-cell:nth-child(2),
                        .dark .fi-ta-header-cell:nth-child(3) {
                            background: rgb(30 41 59) !important;
                        }
                    </style>
                ')
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn() => Blade::render('
                    <script>
                        document.addEventListener("livewire:init", () => {
                            Livewire.on("swal:success", (data) => {
                                Swal.fire({
                                    icon: "success",
                                    title: data[0].title || "Berhasil!",
                                    text: data[0].text || "",
                                    confirmButtonColor: "#10b981",
                                });
                            });
                            Livewire.on("swal:error", (data) => {
                                Swal.fire({
                                    icon: "error",
                                    title: data[0].title || "Gagal!",
                                    text: data[0].text || "",
                                    confirmButtonColor: "#ef4444",
                                });
                            });
                        });
                    </script>
                ')
            )
            ->navigationGroups([
                'Akademik',
                'Setting',
                'Manajemen Surat',
                'Master Data',
                'Data Pendukung',
            ])
            // ->resources([
            //     \App\Filament\Resources\LeaveRequestResource::class,
            // ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Custom widgets loaded via discoverWidgets
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
