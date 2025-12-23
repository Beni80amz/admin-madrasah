<?php

namespace App\Filament\Resources\SiswaMasuks\Tables;

use App\Models\SiswaMasuk;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;

class SiswaMasuksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->nama_lengkap) . '&background=10b981&color=fff')
                    ->size(40),

                TextColumn::make('nomor_surat_penerimaan')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Nomor surat disalin'),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('sekolah_asal')
                    ->label('Sekolah Asal')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                TextColumn::make('kelas_tujuan')
                    ->label('Kelas Tujuan')
                    ->formatStateUsing(fn(?string $state): string => $state ? "Kelas $state" : '-'),

                TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => SiswaMasuk::getStatusOptions()[$state] ?? $state),

                TextColumn::make('student.kelas')
                    ->label('Kelas Aktif')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(SiswaMasuk::getStatusOptions()),
            ])
            ->recordActions([
                // Download Surat Penerimaan PDF
                Action::make('download_surat')
                    ->label('Download Surat')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->url(fn(SiswaMasuk $record): string => route('siswa-masuk.surat-penerimaan', $record->id))
                    ->openUrlInNewTab(),

                // Approve Action
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(SiswaMasuk $record): bool => $record->isPending())
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Siswa Masuk')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui siswa ini? Data siswa akan otomatis ditambahkan ke Data Siswa Aktif.')
                    ->modalSubmitActionLabel('Ya, Setujui')
                    ->form([
                        Textarea::make('catatan_verifikasi')
                            ->label('Catatan Verifikasi (Opsional)')
                            ->placeholder('Tambahkan catatan jika diperlukan')
                            ->rows(2),
                    ])
                    ->action(function (SiswaMasuk $record, array $data) {
                        $record->approve($data['catatan_verifikasi'] ?? null);

                        Notification::make()
                            ->title('Siswa Masuk Disetujui')
                            ->body('Data siswa telah ditambahkan ke Siswa Aktif.')
                            ->success()
                            ->send();
                    }),

                // Reject Action
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(SiswaMasuk $record): bool => $record->isPending())
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Siswa Masuk')
                    ->modalDescription('Apakah Anda yakin ingin menolak siswa ini?')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->form([
                        Textarea::make('catatan_verifikasi')
                            ->label('Alasan Penolakan')
                            ->placeholder('Jelaskan alasan penolakan')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (SiswaMasuk $record, array $data) {
                        $record->reject($data['catatan_verifikasi']);

                        Notification::make()
                            ->title('Siswa Masuk Ditolak')
                            ->body('Permohonan siswa masuk telah ditolak.')
                            ->warning()
                            ->send();
                    }),

                EditAction::make(),
            ]);
    }
}
