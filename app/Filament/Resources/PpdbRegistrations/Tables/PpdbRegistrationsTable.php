<?php

namespace App\Filament\Resources\PpdbRegistrations\Tables;

use App\Models\PpdbRegistration;
use App\Models\Student;
use App\Models\TahunAjaran;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PpdbRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_daftar')
                    ->label('No. Pendaftaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asal_sekolah')
                    ->label('Asal Sekolah')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'gray',
                        'verified' => 'info',
                        'rejected' => 'danger',
                        'accepted' => 'success',
                        'enrolled' => 'primary',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'new' => 'Baru',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        'accepted' => 'Diterima',
                        'enrolled' => 'Terdaftar (Siswa)',
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Action::make('promote')
                    ->label('Jadikan Siswa')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Penerimaan Siswa')
                    ->modalDescription('Apakah Anda yakin ingin mendaftarkan siswa ini sebagai Siswa Aktif? Data akan dipindahkan ke tabel Siswa.')
                    ->visible(fn(PpdbRegistration $record) => $record->status === 'accepted')
                    ->action(function (PpdbRegistration $record) {

                        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

                        // Create Student
                        Student::create([
                            'nama_lengkap' => $record->nama_lengkap,
                            'nisn' => $record->nisn,
                            'nik' => $record->nik,
                            'tempat_lahir' => $record->tempat_lahir,
                            'tanggal_lahir' => $record->tanggal_lahir,
                            'gender' => $record->jenis_kelamin,
                            'agama' => $record->agama,
                            'alamat_domisili' => $record->alamat, // Mapping alamat to alamat_domisili
                            'nama_ayah' => $record->nama_ayah,
                            'nama_ibu' => $record->nama_ibu,
                            'nomor_mobile' => $record->no_hp_ortu,
                            'photo' => $record->dokumen['foto'] ?? null, // Copy photo path
                            'status' => 'aktif',
                            'is_active' => true,
                            'tahun_ajaran_id' => $tahunAjaran?->id,
                            'kelas' => null,
                        ]);

                        // Update PPDB Status
                        $record->update(['status' => 'enrolled']);

                        Notification::make()
                            ->title('Siswa Berhasil Didaftarkan')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
