<?php

namespace App\Filament\Resources\MonitorSlides\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MonitorSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul/Keterangan')
                    ->maxLength(255),
                Select::make('type')
                    ->label('Tipe Slide')
                    ->options([
                        'image' => 'Gambar Upload',
                        'video' => 'Video Upload (MP4)',
                        'youtube' => 'YouTube ID',
                    ])
                    ->default('image')
                    ->reactive()
                    ->required(),
                \Filament\Forms\Components\FileUpload::make('file_path')
                    ->label('Upload File')
                    ->image()
                    ->disk('public')
                    ->directory('monitor-slides')
                    ->hidden(fn(callable $get) => $get('type') === 'youtube')
                    ->label(fn(callable $get) => $get('type') === 'video' ? 'Upload Video' : 'Upload Gambar')
                    ->acceptedFileTypes(fn(callable $get) => $get('type') === 'video' ? ['video/mp4', 'video/webm'] : ['image/jpeg', 'image/png', 'image/webp']),
                TextInput::make('url')
                    ->label('YouTube Video ID')
                    ->helperText('Contoh: dQw4w9WgXcQ (Hanya ID-nya saja, bukan link full)')
                    ->hidden(fn(callable $get) => $get('type') !== 'youtube'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
