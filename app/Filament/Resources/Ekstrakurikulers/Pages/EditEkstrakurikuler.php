<?php

namespace App\Filament\Resources\Ekstrakurikulers\Pages;

use App\Filament\Resources\Ekstrakurikulers\EkstrakurikulerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEkstrakurikuler extends EditRecord
{
    protected static string $resource = EkstrakurikulerResource::class;

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
                        'text' => 'Ekstrakurikuler berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Ekstrakurikuler berhasil diperbarui.',
        ]);
    }
}
