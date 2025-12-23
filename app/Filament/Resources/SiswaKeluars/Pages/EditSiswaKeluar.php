<?php

namespace App\Filament\Resources\SiswaKeluars\Pages;

use App\Filament\Resources\SiswaKeluars\SiswaKeluarResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSiswaKeluar extends EditRecord
{
    protected static string $resource = SiswaKeluarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadSuratPindah')
                ->label('Download Surat Pindah')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->url(fn() => route('siswa-keluar.surat-pindah', $this->record->id))
                ->openUrlInNewTab(),
            Action::make('previewSuratPindah')
                ->label('Preview Surat')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn() => route('siswa-keluar.surat-pindah.preview', $this->record->id))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}

