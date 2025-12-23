<?php

namespace App\Filament\Resources\TugasPokoks\Pages;

use App\Filament\Resources\TugasPokoks\TugasPokokResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTugasPokok extends EditRecord
{
    protected static string $resource = TugasPokokResource::class;

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
                        'text' => 'Tugas pokok berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Tugas pokok berhasil diperbarui.',
        ]);
    }
}
