<?php

namespace App\Filament\Pages;

use App\Services\RdmSyncService;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use BackedEnum;
use UnitEnum;

class RdmSync extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-arrow-path';

    protected string $view = 'filament.pages.rdm-sync';

    protected static ?string $navigationLabel = 'Sinkronisasi RDM';

    protected static UnitEnum|string|null $navigationGroup = 'Setting';

    protected static ?int $navigationSort = 100;

    protected static ?string $title = 'Sinkronisasi Data RDM';

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

                    } catch (\Exception $e) {
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
