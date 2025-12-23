<?php

namespace App\Filament\Resources\SiswaKeluars\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiswaKeluarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->nama_lengkap) . '&color=7F9CF5&background=EBF4FF'),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nis_lokal')
                    ->label('NIS Lokal')
                    ->searchable(),

                TextColumn::make('kelas_terakhir')
                    ->label('Kelas Terakhir')
                    ->sortable(),

                TextColumn::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('sekolah_tujuan')
                    ->label('Sekolah Tujuan')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('alasan_keluar')
                    ->label('Alasan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
            ])
            ->recordActions([
                Action::make('downloadSurat')
                    ->label('Download Surat')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn($record) => route('siswa-keluar.surat-pindah', $record->id))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->defaultSort('tanggal_keluar', 'desc');
    }
}

