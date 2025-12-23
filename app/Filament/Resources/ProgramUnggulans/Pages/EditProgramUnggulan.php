<?php

namespace App\Filament\Resources\ProgramUnggulans\Pages;

use App\Filament\Resources\ProgramUnggulans\ProgramUnggulanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProgramUnggulan extends EditRecord
{
    protected static string $resource = ProgramUnggulanResource::class;

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
                        'text' => 'Program unggulan berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Program unggulan berhasil diperbarui.',
        ]);
    }
}
