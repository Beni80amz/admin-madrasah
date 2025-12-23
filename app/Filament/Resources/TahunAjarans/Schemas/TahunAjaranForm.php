<?php

namespace App\Filament\Resources\TahunAjarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TahunAjaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tahun Ajaran')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Tahun Ajaran')
                            ->placeholder('Contoh: 2025/2026')
                            ->required(),
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required(),
                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Hari Efektif')
                    ->description('Jumlah hari efektif per semester')
                    ->schema([
                        TextInput::make('hari_efektif_ganjil')
                            ->label('Semester Ganjil')
                            ->numeric()
                            ->default(100)
                            ->suffix('hari')
                            ->helperText('Juli - Desember'),
                        TextInput::make('hari_efektif_genap')
                            ->label('Semester Genap')
                            ->numeric()
                            ->default(100)
                            ->suffix('hari')
                            ->helperText('Januari - Juni'),
                    ])
                    ->columns(2),
            ]);
    }
}
