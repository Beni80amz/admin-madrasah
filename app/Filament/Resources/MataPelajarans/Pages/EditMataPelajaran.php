<?php

namespace App\Filament\Resources\MataPelajarans\Pages;

use App\Filament\Resources\MataPelajarans\MataPelajaranResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMataPelajaran extends EditRecord
{
    protected static string $resource = MataPelajaranResource::class;

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
                        'text' => 'Mata pelajaran berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Mata pelajaran berhasil diperbarui.',
        ]);
    }
}
