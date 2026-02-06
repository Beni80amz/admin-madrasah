<?php

namespace App\Filament\Pages;

use App\Services\GoogleDriveService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class GoogleDriveSettings extends Page implements HasInfolists, HasSchemas
{
    use InteractsWithInfolists;
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cloud';

    protected string $view = 'filament.pages.google-drive-settings';

    protected static ?string $navigationLabel = 'Google Drive';

    protected static ?string $title = 'Pengaturan Google Drive';

    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Guru';

    protected static ?int $navigationSort = 0;

    public bool $isConnected = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->isConnected = $user->googleToken !== null;
    }

    public function infolist(Schema $schema): Schema
    {
        $user = Auth::user();
        $googleToken = $user->googleToken;
        $isConnected = $googleToken !== null;

        return $schema
            ->state([
                'status' => $isConnected ? 'connected' : 'disconnected',
            ])
            ->schema([
                Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])
                    ->schema([
                        // Left Column: Status & Guide
                        Grid::make(1)
                            ->schema([
                                ViewEntry::make('status_card')
                                    ->view('filament.infolists.components.google-drive-status-card', [
                                        'status' => $isConnected ? 'connected' : 'disconnected',
                                    ])
                                    ->columnSpanFull()
                                    ->hiddenLabel(),

                                Section::make('Prosedur & Bantuan')
                                    ->visible($isConnected)
                                    ->schema([
                                        ViewEntry::make('quick_guide')
                                            ->view('filament.infolists.components.google-drive-guide')
                                            ->hiddenLabel(),
                                    ])
                                    ->compact(),
                            ])
                            ->columnSpan(['lg' => 1]),

                        // Right Column: Folder Structure or Benefits
                        Grid::make(1)
                            ->schema([
                                Section::make('Dashboard Penyimpanan Cloud')
                                    ->description('Akses cepat ke direktori administrasi di Google Drive Anda.')
                                    ->visible($isConnected && $googleToken?->main_folder_id)
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'sm' => 2,
                                        ])
                                            ->schema([
                                                $this->createFolderEntry('Main', 'Direktori Utama', $googleToken?->main_folder_id, true),
                                                $this->createFolderEntry('Planning', 'Perencanaan (S1)', $googleToken?->planning_folder_id),
                                                $this->createFolderEntry('Execution', 'Pelaksanaan (S2)', $googleToken?->execution_folder_id),
                                                $this->createFolderEntry('Support', 'Bukti Pendukung', $googleToken?->support_folder_id),
                                            ])
                                            ->gap(4),
                                    ])
                                    ->columnSpan('full'),

                                Section::make('Keunggulan Cloud Storage')
                                    ->visible(!$isConnected)
                                    ->schema([
                                        Grid::make([
                                            'default' => 1,
                                            'md' => 1,
                                        ])
                                            ->schema([
                                                $this->createBenefitEntry('heroicon-s-shield-check', 'Data Terenkripsi & Aman', 'File tersimpan langsung di Google Drive pribadi Anda dengan standar keamanan perbankan (OAuth 2.0).', 'primary'),
                                                $this->createBenefitEntry('heroicon-s-square-3-stack-3d', 'Pengarsipan Otomatis', 'Ucapkan selamat tinggal pada pemberkasan manual. Sistem mengelola folder Anda secara cerdas.', 'success'),
                                                $this->createBenefitEntry('heroicon-s-sparkles', 'Manajemen Profesional', 'Meningkatkan akreditasi sekolah dengan sistem dokumentasi digital yang terpercaya dan rapi.', 'info'),
                                            ])
                                            ->gap(4),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 2]),
                    ])
                    ->gap(6),
            ]);
    }


    protected function createFolderEntry(string $id, string $name, ?string $folderId, bool $isMain = false): ViewEntry
    {
        return ViewEntry::make("folder_{$id}")
            ->view('filament.infolists.components.google-drive-folder-card', [
                'name' => $name,
                'link' => $folderId ? "https://drive.google.com/drive/folders/{$folderId}" : null,
                'isMain' => $isMain,
            ]);
    }

    protected function createBenefitEntry(string $icon, string $title, string $description, string $color): ViewEntry
    {
        return ViewEntry::make("benefit_" . str($title)->slug())
            ->view('filament.infolists.components.google-drive-benefit-card', [
                'icon' => $icon,
                'title' => $title,
                'description' => $description,
                'color' => $color,
            ]);
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && ($user->hasRole(['teacher', 'Teacher', 'Guru', 'super_admin', 'Superadmin', 'admin', 'Admin', 'kepala_sekolah', 'Kepala Sekolah']));
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        if (!$this->isConnected) {
            $actions[] = Action::make('connect')
                ->label('Hubungkan Google Drive')
                ->icon('heroicon-o-link')
                ->color('primary')
                ->action(function () {
                    try {
                        $authUrl = app(GoogleDriveService::class)->getAuthUrl();
                        return redirect()->away($authUrl);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal mendapatkan URL Otorisasi')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                });
        } else {
            $actions[] = Action::make('refresh')
                ->label('Segarkan Folder')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        $service = app(GoogleDriveService::class);
                        $service->setupFolderStructure(Auth::user());

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Struktur folder berhasil disegarkan.')
                            ->success()
                            ->send();

                        $this->redirect(static::getUrl());
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                });

            $actions[] = Action::make('disconnect')
                ->label('Putuskan Koneksi')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Putuskan Koneksi Google Drive')
                ->modalDescription('Apakah Anda yakin ingin memutuskan koneksi Google Drive? File yang sudah diunggah akan tetap ada di Drive Anda.')
                ->action(function () {
                    try {
                        $service = app(GoogleDriveService::class);
                        $service->disconnect(Auth::user());

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Koneksi Google Drive berhasil diputuskan.')
                            ->success()
                            ->send();

                        $this->redirect(static::getUrl());
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                });
        }

        return $actions;
    }
}
