<?php

namespace App\Filament\Resources\OperationalHours\Pages;

use App\Filament\Resources\OperationalHours\OperationalHourResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOperationalHour extends EditRecord
{
    protected static string $resource = OperationalHourResource::class;

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
                        'text' => 'Jam operasional berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Jam operasional berhasil diperbarui.',
        ]);
    }
}
