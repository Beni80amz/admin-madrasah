<?php

namespace App\Filament\Resources\StrukturOrganisasis\Pages;

use App\Filament\Resources\StrukturOrganisasis\StrukturOrganisasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStrukturOrganisasi extends CreateRecord
{
    protected static string $resource = StrukturOrganisasiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Anggota struktur organisasi berhasil ditambahkan.',
        ]);
    }
}
