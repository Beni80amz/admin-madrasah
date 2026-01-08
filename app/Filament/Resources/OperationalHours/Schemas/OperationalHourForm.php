<?php

namespace App\Filament\Resources\OperationalHours\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OperationalHourForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jam Operasional')
                    ->schema([
                        TextInput::make('hari')
                            ->label('Hari')
                            ->placeholder("Contoh: Senin - Jum'at, Sabtu, Minggu")
                            ->required()
                            ->maxLength(255),
                        TextInput::make('waktu')
                            ->label('Waktu / Keterangan')
                            ->placeholder('Contoh: KBM Aktif, 07:00 - 14:00, Libur')
                            ->required()
                            ->maxLength(255),
                        TimePicker::make('time_in')
                            ->label('Jam Masuk (Sistem Absensi)')
                            ->seconds(false)
                            ->timezone('Asia/Jakarta'),
                        TimePicker::make('time_out')
                            ->label('Jam Pulang (Sistem Absensi)')
                            ->seconds(false)
                            ->timezone('Asia/Jakarta'),
                        TextInput::make('urutan')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Toggle::make('is_libur')
                            ->label('Tandai sebagai Libur')
                            ->helperText('Jika aktif, akan ditampilkan dengan badge merah di frontend')
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Tampilkan di halaman Kontak')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
