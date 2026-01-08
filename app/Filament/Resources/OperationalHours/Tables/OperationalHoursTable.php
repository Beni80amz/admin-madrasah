<?php

namespace App\Filament\Resources\OperationalHours\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OperationalHoursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hari')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('waktu')
                    ->label('Keterangan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('time_in')
                    ->label('Masuk')
                    ->time('H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable(),
                TextColumn::make('time_out')
                    ->label('Pulang')
                    ->time('H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable(),
                IconColumn::make('is_libur')
                    ->boolean()
                    ->label('Libur?'),
                TextColumn::make('urutan')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif?'),
            ])
            ->filters([
                //
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
