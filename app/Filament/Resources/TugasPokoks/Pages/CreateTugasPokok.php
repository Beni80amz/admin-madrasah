<?php

namespace App\Filament\Resources\TugasPokoks\Pages;

use App\Filament\Resources\TugasPokoks\TugasPokokResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTugasPokok extends CreateRecord
{
    protected static string $resource = TugasPokokResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Tugas pokok berhasil ditambahkan.',
        ]);
    }
}
