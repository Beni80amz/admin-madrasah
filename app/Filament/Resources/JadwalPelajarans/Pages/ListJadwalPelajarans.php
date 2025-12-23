<?php

namespace App\Filament\Resources\JadwalPelajarans\Pages;

use App\Filament\Resources\JadwalPelajarans\JadwalPelajaranResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListJadwalPelajarans extends ListRecords
{
    protected static string $resource = JadwalPelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manageJadwal')
                ->label('Kelola Jadwal')
                ->icon('heroicon-o-calendar-days')
                ->color('success')
                ->url(fn() => JadwalPelajaranResource::getUrl('manage')),
        ];
    }
}
