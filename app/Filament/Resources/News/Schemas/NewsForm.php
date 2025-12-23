<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Otomatis dibuat dari judul'),
                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'Prestasi' => 'Prestasi',
                        'Agenda' => 'Agenda',
                        'Sosial' => 'Sosial',
                        'Kegiatan' => 'Kegiatan',
                        'Pengumuman' => 'Pengumuman',
                        'Rihlah' => 'Rihlah',
                        'Haflah' => 'Haflah',
                        'Praktik' => 'Praktik',
                        'Umum' => 'Umum',
                    ])
                    ->default('Umum')
                    ->required(),
                FileUpload::make('featured_image')
                    ->label('Gambar Utama')
                    ->image()
                    ->disk('public')
                    ->directory('news')
                    ->visibility('public')
                    ->imageEditor()
                    ->columnSpanFull(),
                Textarea::make('excerpt')
                    ->label('Ringkasan')
                    ->rows(2)
                    ->columnSpanFull()
                    ->helperText('Ringkasan singkat untuk preview berita'),
                RichEditor::make('content')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull(),
                TagsInput::make('tags')
                    ->label('Tags')
                    ->placeholder('Ketik tag lalu tekan Enter')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Tanggal Publikasi'),
            ]);
    }
}


