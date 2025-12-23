<?php

namespace App\Filament\Resources\Rombels\Pages;

use App\Filament\Resources\Rombels\RombelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRombel extends EditRecord
{
    protected static string $resource = RombelResource::class;

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
                        'text' => 'Rombel berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Rombel berhasil diperbarui.',
        ]);
    }
}
