<?php

namespace App\Filament\Resources\ProfileMadrasahs\Pages;

use App\Filament\Resources\ProfileMadrasahs\ProfileMadrasahResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProfileMadrasah extends EditRecord
{
    protected static string $resource = ProfileMadrasahResource::class;

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
                        'text' => 'Profil madrasah berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Profil madrasah berhasil diperbarui.',
        ]);
    }
}
