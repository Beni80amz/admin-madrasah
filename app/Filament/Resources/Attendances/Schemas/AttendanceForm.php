<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable(),
                        DatePicker::make('date')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'hadir' => 'Hadir',
                                'telat' => 'Telat',
                                'izin' => 'Izin',
                                'sakit' => 'Sakit',
                                'alpha' => 'Alpha',
                            ])
                            ->required(),
                    ])->columns(3),

                Section::make('Waktu & Lokasi')
                    ->schema([
                        TimePicker::make('time_in')->seconds(false),
                        TimePicker::make('time_out')->seconds(false),
                    ])->columns(2),

                Section::make('Kalkulasi')
                    ->schema([
                        TextInput::make('keterlambatan')
                            ->numeric()
                            ->suffix('Menit'),
                        TextInput::make('lembur')
                            ->numeric()
                            ->suffix('Menit'),
                    ])->columns(2),

                Section::make('Catatan')
                    ->schema([
                        Textarea::make('note')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
