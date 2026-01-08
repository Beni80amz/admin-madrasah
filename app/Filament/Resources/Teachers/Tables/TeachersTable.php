<?php

namespace App\Filament\Resources\Teachers\Tables;

use App\Exports\TeachersExport;
use App\Exports\TeachersTemplateExport;
use App\Imports\TeachersImport;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class TeachersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable(),
                TextColumn::make('nip')
                    ->label('NIP/NIK')
                    ->searchable(),
                TextColumn::make('nuptk')
                    ->label('NUPTK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('npk_peg_id')
                    ->label('NPK/Peg.ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jabatan.nama')
                    ->label('Jabatan')
                    ->searchable(),
                TextColumn::make('tugasPokok.nama')
                    ->label('Tugas Pokok')
                    ->searchable(),
                TextColumn::make('tugasTambahan.nama')
                    ->label('Tugas Tambahan')
                    ->searchable(),
                TextColumn::make('kelas_rombel')
                    ->label('Kelas/Rombel')
                    ->getStateUsing(fn($record) => $record->kelas_rombel),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'PNS' => 'success',
                        'P3K' => 'info',
                        default => 'warning',
                    }),
                TextColumn::make('sertifikasi')
                    ->label('Sertifikasi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Sudah' => 'success',
                        default => 'danger',
                    }),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\Action::make('resetDevice')
                    ->label('Reset Perangkat')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Device ID')
                    ->modalDescription('Apakah Anda yakin ingin mereset Device ID pengguna ini? Pengguna harus login ulang di perangkat baru.')
                    ->modalSubmitActionLabel('Ya, Reset')
                    ->action(function (Teacher $record) {
                        if ($record->user_id) {
                            $user = \App\Models\User::find($record->user_id);
                            if ($user) {
                                $user->update(['device_id' => null]);
                                \Filament\Notifications\Notification::make()
                                    ->title('Device ID berhasil di-reset')
                                    ->success()
                                    ->send();
                            }
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('User belum terhubung')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->headerActions([
                // Download Template
                Action::make('downloadTemplate')
                    ->label('Download Template')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->action(function () {
                        return Excel::download(new TeachersTemplateExport, 'template_data_guru.xlsx');
                    }),

                // Import Excel
                Action::make('importExcel')
                    ->label('Import Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('info')
                    ->form([
                        FileUpload::make('file')
                            ->label('File Excel')
                            ->acceptedFileTypes([
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $filePath = storage_path('app/private/' . $data['file']);
                        Excel::import(new TeachersImport, $filePath);

                        Notification::make()
                            ->title('Import berhasil!')
                            ->success()
                            ->send();
                    }),

                // Export Excel
                Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(new TeachersExport, 'data_guru_' . now()->format('Y-m-d') . '.xlsx');
                    }),

                // Export PDF
                Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('danger')
                    ->action(function () {
                        $teachers = Teacher::with(['jabatan', 'tugasPokok', 'tugasTambahan'])->get();
                        $profile = \App\Models\ProfileMadrasah::first();
                        $tahunAjaran = \App\Models\TahunAjaran::getActive();

                        $pdf = Pdf::loadView('exports.teachers-pdf', [
                            'teachers' => $teachers,
                            'profile' => $profile,
                            'tahunAjaran' => $tahunAjaran,
                        ]);

                        return response()->streamDownload(
                            fn() => print ($pdf->output()),
                            'data_guru_' . now()->format('Y-m-d') . '.pdf'
                        );
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
