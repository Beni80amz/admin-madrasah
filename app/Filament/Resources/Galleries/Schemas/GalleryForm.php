<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'photo' => 'Foto',
                        'video' => 'Video',
                    ])
                    ->default('photo')
                    ->required()
                    ->live(),
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'KBM' => 'KBM',
                        'Praktikum' => 'Praktikum',
                        'Prestasi' => 'Prestasi',
                        'Akademik' => 'Akademik',
                        'Kegiatan' => 'Kegiatan',
                        'Rihlah' => 'Rihlah',
                        'Haflah' => 'Haflah',
                        'Ekstrakurikuler' => 'Ekstrakurikuler',
                        'Umum' => 'Umum',
                    ])
                    ->default('Umum'),
                FileUpload::make('image')
                    ->label(fn($get) => $get('type') === 'video' ? 'Thumbnail Video' : 'Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('galleries')
                    ->visibility('public')
                    ->imageEditor()
                    ->required(),
                TextInput::make('video_url')
                    ->label('URL Video (YouTube/Vimeo)')
                    ->url()
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->visible(fn($get) => $get('type') === 'video'),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('urutan')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_featured')
                    ->label('Featured')
                    ->helperText('Tampilkan di beranda'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}

