<?php

namespace App\Filament\Resources\Jabatans\Pages;

use App\Filament\Resources\Jabatans\JabatanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJabatan extends CreateRecord
{
    protected static string $resource = JabatanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Jabatan baru berhasil ditambahkan.',
        ]);
    }
}
