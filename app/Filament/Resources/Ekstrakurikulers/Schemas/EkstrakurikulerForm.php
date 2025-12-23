<?php

namespace App\Filament\Resources\Ekstrakurikulers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EkstrakurikulerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Ekstrakurikuler')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Pramuka, Olahraga'),
                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(2)
                    ->columnSpanFull()
                    ->placeholder('Deskripsi singkat (muncul saat hover)'),
                FileUpload::make('gambar')
                    ->label('Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('ekstrakurikuler')
                    ->visibility('public')
                    ->imageEditor()
                    ->columnSpanFull(),
                TextInput::make('urutan')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
