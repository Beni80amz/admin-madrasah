<?php

namespace App\Filament\Resources\TugasTambahans\Pages;

use App\Filament\Resources\TugasTambahans\TugasTambahanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTugasTambahan extends EditRecord
{
    protected static string $resource = TugasTambahanResource::class;

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
                        'text' => 'Tugas tambahan berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Tugas tambahan berhasil diperbarui.',
        ]);
    }
}
