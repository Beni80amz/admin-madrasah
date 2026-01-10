<?php

namespace App\Filament\Pages;

use App\Services\RdmSyncService;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class RdmSync extends Page
{
    protected string $view = 'filament.pages.rdm-sync';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-arrow-path';
    }

    public static function getNavigationLabel(): string
    {
        return 'Sinkronisasi RDM';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Setting';
    }

    public static function getNavigationSort(): ?int
    {
        return 100;
    }

    public function getTitle(): string
    {
        return 'Sinkronisasi Data RDM';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncAll')
                ->label('Sync Semua Data')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Sinkronisasi')
                ->modalDescription('Ini akan men-sync semua data Guru dan Siswa dari database RDM. Lanjutkan?')
                ->action(function () {
                    try {
                        $service = new RdmSyncService();
                        $results = $service->syncAll();

                        Notification::make()
                            ->title('Sinkronisasi Berhasil!')
                            ->body(sprintf(
                                "Guru: %d baru, %d diperbarui, %d error\nSiswa: %d baru, %d diperbarui, %d error",
                                $results['teachers']['created'],
                                $results['teachers']['updated'],
                                $results['teachers']['errors'],
                                $results['students']['created'],
                                $results['students']['updated'],
                                $results['students']['errors']
                            ))
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Sinkronisasi')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('syncTeachers')
                ->label('Sync Guru Saja')
                ->icon('heroicon-o-user-circle')
                ->color('info')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        $service = new RdmSyncService();
                        $results = $service->syncTeachersFromRdm();

                        Notification::make()
                            ->title('Sinkronisasi Guru Berhasil!')
                            ->body(sprintf(
                                "%d baru, %d diperbarui, %d error",
                                $results['created'],
                                $results['updated'],
                                $results['errors']
                            ))
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Sinkronisasi Guru')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('syncStudents')
                ->label('Sync Siswa Saja')
                ->icon('heroicon-o-users')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        $service = new RdmSyncService();
                        $results = $service->syncStudentsFromRdm();

                        Notification::make()
                            ->title('Sinkronisasi Siswa Berhasil!')
                            ->body(sprintf(
                                "%d baru, %d diperbarui, %d error",
                                $results['created'],
                                $results['updated'],
                                $results['errors']
                            ))
                            ->success()
                            ->send();

                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Sync Error: ' . $e->getMessage());
                        Notification::make()
                            ->title('Gagal Sinkronisasi Siswa')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
