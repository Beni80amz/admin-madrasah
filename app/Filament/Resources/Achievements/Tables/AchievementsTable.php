<?php

namespace App\Filament\Resources\Achievements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AchievementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->circular()
                    ->state(function (\App\Models\Achievement $record) {
                        if ($record->photo) {
                            return $record->photo;
                        }

                        if ($record->type === 'siswa') {
                            $student = \App\Models\Student::where('nama_lengkap', $record->nama)->first();
                            return $student?->photo;
                        }

                        if ($record->type === 'guru') {
                            $teacher = \App\Models\Teacher::where('nama_lengkap', $record->nama)->first();
                            return $teacher?->photo;
                        }

                        return null;
                    }),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('prestasi')
                    ->searchable(),
                TextColumn::make('tingkat')
                    ->searchable(),
                TextColumn::make('kategori')
                    ->searchable(),
                TextColumn::make('tahun')
                    ->searchable(),
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
