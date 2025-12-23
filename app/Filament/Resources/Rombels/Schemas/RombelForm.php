<?php

namespace App\Filament\Resources\Rombels\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RombelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                Select::make('kelas_id')
                    ->relationship('kelas', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('wali_kelas_id')
                    ->relationship('waliKelas', 'nama_lengkap')
                    ->searchable()
                    ->preload(),
                TextInput::make('kapasitas')
                    ->required()
                    ->numeric()
                    ->default(30),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
