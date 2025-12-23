<?php

namespace App\Filament\Resources\StrukturOrganisasis\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StrukturOrganisasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('level')
                    ->label('Level')
                    ->badge()
                    ->color(fn(int $state): string => match ($state) {
                        1 => 'success',
                        2 => 'info',
                        3 => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(int $state): string => "Level $state")
                    ->sortable(),

                ImageColumn::make('teacher.photo')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->nama_display) . '&background=10b981&color=fff'),

                TextColumn::make('nama_display')
                    ->label('Nama')
                    ->searchable(query: function ($query, string $search) {
                        return $query->where(function ($q) use ($search) {
                            $q->where('nama', 'like', "%{$search}%")
                                ->orWhereHas('teacher', fn($t) => $t->where('nama_lengkap', 'like', "%{$search}%"));
                        });
                    }),

                TextColumn::make('jabatan_struktural')
                    ->label('Jabatan')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('level', 'asc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
