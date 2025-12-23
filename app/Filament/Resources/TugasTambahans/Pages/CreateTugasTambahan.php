<?php

namespace App\Filament\Resources\TugasTambahans\Pages;

use App\Filament\Resources\TugasTambahans\TugasTambahanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTugasTambahan extends CreateRecord
{
    protected static string $resource = TugasTambahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Tugas tambahan berhasil ditambahkan.',
        ]);
    }
}
