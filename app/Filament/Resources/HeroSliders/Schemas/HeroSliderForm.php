<?php

namespace App\Filament\Resources\HeroSliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gambar Slider')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->directory('hero-sliders')
                            ->disk('public')
                            ->imageEditor()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Konten (Opsional)')
                    ->description('Teks dan tombol akan ditampilkan di atas gambar')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->placeholder('Contoh: Selamat Datang'),
                        TextInput::make('subtitle')
                            ->label('Subjudul')
                            ->placeholder('Contoh: Di Madrasah Terbaik'),
                        TextInput::make('button_text')
                            ->label('Teks Tombol')
                            ->placeholder('Contoh: Pelajari Lebih Lanjut'),
                        TextInput::make('button_link')
                            ->label('Link Tombol')
                            ->placeholder('Contoh: /profil')
                            ->url(false),
                    ])
                    ->columns(2),

                Section::make('Pengaturan')
                    ->schema([
                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->helperText('Angka lebih kecil ditampilkan lebih dulu'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
