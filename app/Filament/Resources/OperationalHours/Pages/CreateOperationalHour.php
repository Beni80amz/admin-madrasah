<?php

namespace App\Filament\Resources\OperationalHours\Pages;

use App\Filament\Resources\OperationalHours\OperationalHourResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOperationalHour extends CreateRecord
{
    protected static string $resource = OperationalHourResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Jam operasional berhasil ditambahkan.',
        ]);
    }
}
