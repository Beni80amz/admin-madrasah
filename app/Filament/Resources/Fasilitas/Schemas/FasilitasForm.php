<?php

namespace App\Filament\Resources\Fasilitas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FasilitasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Fasilitas')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Lab Komputer'),
                TextInput::make('icon')
                    ->label('Icon')
                    ->required()
                    ->default('school')
                    ->helperText('Gunakan nama icon dari Material Symbols (contoh: computer, science, mosque, wifi)')
                    ->placeholder('computer'),
                TextInput::make('urutan')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->default(0)
                    ->helperText('Semakin kecil angka, semakin di depan posisinya'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText('Fasilitas yang tidak aktif tidak akan ditampilkan di website'),
            ]);
    }
}
