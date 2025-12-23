<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

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
                        'title' => 'Berita Dihapus!',
                        'text' => 'Berita berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Berita Diperbarui!',
            'text' => 'Berita berhasil diperbarui.',
        ]);
    }
}
