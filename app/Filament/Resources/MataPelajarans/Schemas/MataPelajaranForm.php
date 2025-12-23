<?php

namespace App\Filament\Resources\MataPelajarans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MataPelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                Select::make('kelompok')
                    ->label('Kelompok')
                    ->options([
                        'A-Wajib' => 'A-Wajib',
                        'B-Pilihan' => 'B-Pilihan',
                        'C-Muatan Lokal' => 'C-Muatan Lokal',
                        'Kokurikuler' => 'Kokurikuler',
                    ])
                    ->required(),
                TextInput::make('kkm')
                    ->required()
                    ->numeric()
                    ->default(75),
                TextInput::make('beban_jam_minggu')
                    ->label('Beban Jam/Minggu')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->suffix('jam'),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
