<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->record->ensureUserExists();

        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Data guru berhasil ditambahkan. User akun juga telah diproses.',
        ]);
    }
}
