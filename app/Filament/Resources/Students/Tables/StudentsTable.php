<?php

namespace App\Filament\Resources\Students\Tables;

use App\Models\Rombel;
use App\Models\Student;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('status', Student::STATUS_AKTIF))
            ->columns([
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record?->nama_lengkap ?? 'Student') . '&background=10b981&color=fff&size=100'),
                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nis_lokal')
                    ->label('NIS Lokal')
                    ->searchable(),
                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Gender')
                    ->searchable(),
                TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->searchable(),
                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_ibu')
                    ->label('Nama Ibu')
                    ->searchable(),
                TextColumn::make('nama_ayah')
                    ->label('Nama Ayah')
                    ->searchable(),
                TextColumn::make('nomor_mobile')
                    ->label('No. Mobile')
                    ->searchable(),
                TextColumn::make('nomor_pip')
                    ->label('No. PIP')
                    ->searchable(),
                TextColumn::make('alamat_kk')
                    ->label('Alamat KK')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('alamat_domisili')
                    ->label('Alamat Domisili')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: false),
                IconColumn::make('is_active')
                    ->label('Status')
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
                SelectFilter::make('kelas')
                    ->label('Kelas/Rombel')
                    ->options(function () {
                        // Helper to convert Roman numerals to Arabic
                        $romanToArabic = function ($roman) {
                            $romans = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100];
                            $roman = strtoupper(trim($roman));
                            if (is_numeric($roman)) {
                                return $roman;
                            }
                            $result = 0;
                            $length = strlen($roman);
                            for ($i = 0; $i < $length; $i++) {
                                $current = $romans[$roman[$i]] ?? 0;
                                $next = ($i + 1 < $length) ? ($romans[$roman[$i + 1]] ?? 0) : 0;
                                if ($current < $next) {
                                    $result -= $current;
                                } else {
                                    $result += $current;
                                }
                            }
                            return $result > 0 ? (string) $result : $roman;
                        };

                        return Rombel::with('kelas')
                            ->get()
                            ->mapWithKeys(function ($rombel) use ($romanToArabic) {
                                $tingkat = $romanToArabic($rombel->kelas?->tingkat ?? '');
                                $rombelNama = $rombel->nama ?? '';
                                $value = $tingkat . '-' . $rombelNama;
                                $label = ($rombel->kelas?->nama ?? '') . ' - ' . $value;
                                return [$value => $label];
                            })
                            ->sort()
                            ->toArray();
                    })
                    ->searchable()
                    ->preload(),

                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
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
                    ->action(function (Student $record) {
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
