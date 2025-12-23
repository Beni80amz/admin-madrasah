<?php

namespace App\Filament\Resources\Rombels\Pages;

use App\Filament\Resources\Rombels\RombelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRombel extends CreateRecord
{
    protected static string $resource = RombelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Rombel baru berhasil ditambahkan.',
        ]);
    }
}
