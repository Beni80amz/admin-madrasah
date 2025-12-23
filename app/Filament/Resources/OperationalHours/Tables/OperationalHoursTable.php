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
                    ->searchable()
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
