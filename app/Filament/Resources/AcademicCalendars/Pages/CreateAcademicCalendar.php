<?php

namespace App\Filament\Resources\AcademicCalendars\Pages;

use App\Filament\Resources\AcademicCalendars\AcademicCalendarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAcademicCalendar extends CreateRecord
{
    protected static string $resource = AcademicCalendarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Kegiatan kalender akademik berhasil ditambahkan.',
        ]);
    }
}
