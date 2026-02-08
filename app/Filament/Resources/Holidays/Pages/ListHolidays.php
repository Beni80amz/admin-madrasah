<?php

namespace App\Filament\Resources\Holidays\Pages;

use App\Filament\Resources\Holidays\HolidayResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHolidays extends ListRecords
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('sync_from_academic_calendar')
                ->label('Sync dari Kalender Akademik')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->action(function () {
                    $academicEntries = \App\Models\AcademicCalendar::where('kategori', 'Hari Libur')->get();
                    $count = 0;
                    foreach ($academicEntries as $entry) {
                        \App\Models\Holiday::updateOrCreate(
                            ['academic_calendar_id' => $entry->id],
                            [
                                'title' => $entry->nama_kegiatan,
                                'start_date' => $entry->tanggal_mulai,
                                'end_date' => $entry->tanggal_selesai,
                                'description' => $entry->keterangan,
                            ]
                        );
                        $count++;
                    }

                    \Filament\Notifications\Notification::make()
                        ->title("Berhasil menyinkronkan {$count} data")
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
