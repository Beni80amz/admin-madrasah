<?php

namespace App\Filament\Resources\Alumnis\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AlumniForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Alumni')
                    ->schema([
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->placeholder('Contoh: Ahmad Fadillah')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('tahun_lulus')
                            ->label('Tahun Lulus')
                            ->placeholder('Contoh: 2024')
                            ->required(),

                        TextInput::make('nomor_mobile')
                            ->label('Nomor Mobile')
                            ->placeholder('Contoh: 081234567890')
                            ->tel(),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->placeholder('Contoh: Jl. Merdeka No. 15, Kota Depok')
                            ->rows(3)
                            ->columnSpanFull(),

                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->directory('alumni')
                            ->disk('public')
                            ->imageEditor()
                            ->circleCropper()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
