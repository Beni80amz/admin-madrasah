<?php

namespace App\Filament\Resources\Kelas\Pages;

use App\Filament\Resources\Kelas\KelasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKelas extends EditRecord
{
    protected static string $resource = KelasResource::class;

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
                        'text' => 'Kelas berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Kelas berhasil diperbarui.',
        ]);
    }
}
