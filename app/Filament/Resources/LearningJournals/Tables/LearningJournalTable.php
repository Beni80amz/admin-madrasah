<?php

namespace App\Filament\Resources\LearningJournals\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class LearningJournalTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('D, d M Y')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Guru')
                    ->getStateUsing(fn($record) => $record->user?->teacher?->nama_lengkap ?? $record->user?->name)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('mataPelajaran.nama')
                    ->label('Mapel')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rombel.nama')
                    ->label('Kelas')
                    ->getStateUsing(fn($record) => "{$record->rombel?->kelas?->nama} - {$record->rombel?->nama}")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pertemuan_ke')
                    ->label('Pertemuan')
                    ->toggleable(),
                TextColumn::make('absensi_summary')
                    ->label('Absensi (S/I/A)')
                    ->getStateUsing(fn($record) => "{$record->absensi_s} / {$record->absensi_i} / {$record->absensi_a}")
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Guru')
                    ->options(\App\Models\Teacher::whereNotNull('user_id')->pluck('nama_lengkap', 'user_id'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->relationship('mataPelajaran', 'nama')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('rombel_id')
                    ->label('Rombel')
                    ->relationship('rombel', 'nama')
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->kelas?->nama} - {$record->nama}")
                    ->searchable()
                    ->preload(),
                Filter::make('date')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
