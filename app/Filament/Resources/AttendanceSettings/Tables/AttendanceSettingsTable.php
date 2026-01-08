<?php

namespace App\Filament\Resources\AttendanceSettings\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AttendanceSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->sortable()->searchable(),
                TextColumn::make('value')->limit(50),
                TextColumn::make('description')->limit(50),
            ])
            ->actions([
                //
            ]);
    }
}
