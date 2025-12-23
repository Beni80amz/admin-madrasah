<?php

namespace App\Filament\Resources\JadwalPelajarans\Tables;

use App\Models\JadwalPelajaran;
use App\Models\Rombel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class JadwalPelajaransTable
{
    public static function configure(Table $table): Table
    {
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

        return $table
            ->defaultSort('hari')
            ->columns([
                TextColumn::make('rombel.kelas.nama')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('rombel.nama')
                    ->label('Rombel')
                    ->sortable(),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'ganjil' => 'info',
                        'genap' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                TextColumn::make('hari')
                    ->label('Hari')
                    ->badge()
                    ->color('warning')
                    ->sortable(),

                TextColumn::make('jam_ke')
                    ->label('Jam Ke')
                    ->formatStateUsing(fn(int $state): string => "Jam ke-{$state}")
                    ->sortable(),

                TextColumn::make('jam_mulai')
                    ->label('Mulai')
                    ->time('H:i'),

                TextColumn::make('jam_selesai')
                    ->label('Selesai')
                    ->time('H:i'),

                TextColumn::make('mataPelajaran.nama')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('teacher.nama_lengkap')
                    ->label('Guru')
                    ->searchable()
                    ->limit(20),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options(JadwalPelajaran::getSemesterOptions()),

                SelectFilter::make('hari')
                    ->label('Hari')
                    ->options(JadwalPelajaran::getHariOptions()),

                SelectFilter::make('rombel_id')
                    ->label('Rombel')
                    ->options(function () use ($romanToArabic) {
                        return Rombel::with('kelas')
                            ->get()
                            ->mapWithKeys(function ($rombel) use ($romanToArabic) {
                                $label = ($rombel->kelas?->nama ?? '') . ' - ' . $rombel->nama;
                                return [$rombel->id => $label];
                            })
                            ->toArray();
                    })
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
