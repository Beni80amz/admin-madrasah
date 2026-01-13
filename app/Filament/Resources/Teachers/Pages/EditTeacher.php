<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(function () {
                    $this->dispatch('swal:success', [
                        'title' => 'Data Dihapus!',
                        'text' => 'Data guru berhasil dihapus dari database.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->ensureUserExists();

        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Data guru berhasil diperbarui.',
        ]);
    }
}
