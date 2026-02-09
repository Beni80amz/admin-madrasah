<?php

namespace App\Filament\Resources\Downloads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class DownloadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi File')
                    ->description('Masukan detail file yang akan diunduh')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Dokumen')
                            ->placeholder('Contoh: Formulir Pendaftaran 2024')
                            ->required()
                            ->maxLength(255),

                        Grid::make(2)
                            ->schema([
                                Select::make('category')
                                    ->label('Kategori')
                                    ->options([
                                        'Pendaftaran' => 'Pendaftaran',
                                        'Akademik' => 'Akademik',
                                        'Brosur' => 'Brosur',
                                        'Surat' => 'Surat',
                                        'Lainnya' => 'Lainnya',
                                    ])
                                    ->default('Dokumen')
                                    ->required(),

                                TextInput::make('sort_order')
                                    ->label('Urutan Tampilan')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->helperText('Angka lebih kecil akan muncul lebih dulu'),
                            ]),

                        Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->placeholder('Jelaskan isi dokumen ini...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Upload File')
                    ->description('Pilih file yang ingin dipublikasikan')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Pilih File')
                            ->disk('public')
                            ->directory('downloads')
                            ->visibility('public')
                            ->required()
                            ->preserveFilenames()
                            ->maxSize(10240) // 10MB
                            ->helperText('Format yang disarankan: PDF, JPG, PNG, DOCX (Maks. 10MB)'),

                        Toggle::make('is_active')
                            ->label('Publikasikan')
                            ->helperText('Jika dinonaktifkan, file tidak akan muncul di halaman pengunjung')
                            ->default(true),

                        TextInput::make('download_count')
                            ->label('Jumlah Unduhan')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->helperText('Dihitung secara otomatis saat user mendownload'),
                    ]),
            ]);
    }
}
