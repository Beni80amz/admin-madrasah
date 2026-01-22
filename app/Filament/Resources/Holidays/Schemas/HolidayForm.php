<?php

namespace App\Filament\Resources\Holidays\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HolidayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->label('Judul'),
                DatePicker::make('start_date')
                    ->required()
                    ->label('Tanggal Mulai'),
                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->afterOrEqual('start_date')
                    ->helperText('Kosongkan jika hanya 1 hari'),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
