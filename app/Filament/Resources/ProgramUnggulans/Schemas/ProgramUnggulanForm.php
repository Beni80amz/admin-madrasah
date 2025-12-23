<?php

namespace App\Filament\Resources\ProgramUnggulans\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProgramUnggulanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Program')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Tahfidz Al-Quran'),
                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull()
                    ->placeholder('Deskripsi singkat tentang program unggulan'),
                TextInput::make('icon')
                    ->label('Icon')
                    ->required()
                    ->default('school')
                    ->helperText('Gunakan nama icon dari Material Symbols (contoh: menu_book, smart_toy, translate)')
                    ->placeholder('menu_book'),
                FileUpload::make('gambar')
                    ->label('Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('program-unggulan')
                    ->visibility('public')
                    ->imageEditor()
                    ->columnSpanFull(),
                TextInput::make('urutan')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->default(0)
                    ->helperText('Semakin kecil angka, semakin di depan posisinya'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText('Program yang tidak aktif tidak akan ditampilkan di website'),
            ]);
    }
}

