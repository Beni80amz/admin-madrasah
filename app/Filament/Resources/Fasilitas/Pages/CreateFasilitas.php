<?php

namespace App\Filament\Resources\Fasilitas\Pages;

use App\Filament\Resources\Fasilitas\FasilitasResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFasilitas extends CreateRecord
{
    protected static string $resource = FasilitasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Fasilitas baru berhasil ditambahkan.',
        ]);
    }
}
