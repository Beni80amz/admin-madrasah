<?php

namespace App\Filament\Resources\ProgramUnggulans\Pages;

use App\Filament\Resources\ProgramUnggulans\ProgramUnggulanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProgramUnggulan extends CreateRecord
{
    protected static string $resource = ProgramUnggulanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Program unggulan baru berhasil ditambahkan.',
        ]);
    }
}
