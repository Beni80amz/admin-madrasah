<?php

namespace App\Filament\Resources\Achievements\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AchievementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Prestasi')
                    ->schema([
                        Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'siswa' => 'Siswa',
                                'guru' => 'Guru',
                            ])
                            ->required()
                            ->native(false),

                        Select::make('kategori')
                            ->label('Kategori')
                            ->options([
                                'Akademik' => 'Akademik',
                                'Keagamaan' => 'Keagamaan',
                                'Olahraga' => 'Olahraga',
                                'Seni dan Budaya' => 'Seni dan Budaya',
                            ])
                            ->required()
                            ->native(false),

                        Select::make('jenis')
                            ->label('Jenis')
                            ->options([
                                'Perorangan' => 'Perorangan',
                                'Kelompok' => 'Kelompok',
                            ])
                            ->default('Perorangan')
                            ->required()
                            ->native(false),

                        TextInput::make('prestasi')
                            ->label('Nama Prestasi')
                            ->placeholder('Contoh: Juara 1 Olimpiade Matematika')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('event')
                            ->label('Nama Event/Kompetisi')
                            ->placeholder('Contoh: KSM Tingkat Kota Depok')
                            ->required(),

                        Select::make('peringkat')
                            ->label('Peringkat')
                            ->options([
                                1 => 'Juara 1',
                                2 => 'Juara 2',
                                3 => 'Juara 3',
                                4 => 'Harapan 1',
                                5 => 'Harapan 2',
                                6 => 'Harapan 3',
                            ])
                            ->required()
                            ->native(false),

                        Select::make('tingkat')
                            ->label('Tingkat')
                            ->options([
                                'Kecamatan' => 'Kecamatan',
                                'Kota/Kabupaten' => 'Kota/Kabupaten',
                                'Provinsi' => 'Provinsi',
                                'Nasional' => 'Nasional',
                                'Internasional' => 'Internasional',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('tahun')
                            ->label('Tahun')
                            ->placeholder('2024')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Informasi Peserta')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Peserta')
                            ->placeholder('Contoh: Ahmad Fauzi')
                            ->required(),

                        TextInput::make('kelas')
                            ->label('Kelas')
                            ->placeholder('Contoh: Kelas 6'),

                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->directory('achievements')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Deskripsi')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Tambahan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
