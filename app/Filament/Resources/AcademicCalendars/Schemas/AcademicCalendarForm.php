<?php

namespace App\Filament\Resources\AcademicCalendars\Schemas;

use App\Models\TahunAjaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AcademicCalendarForm
{
    public static function configure(Schema $schema): Schema
    {
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

        return $schema
            ->components([
                TextInput::make('nama_kegiatan')
                    ->required(),
                DatePicker::make('tanggal_mulai')
                    ->required(),
                DatePicker::make('tanggal_selesai'),
                Select::make('semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->required(),
                TextInput::make('tahun_ajaran')
                    ->default($tahunAjaranAktif?->nama)
                    ->required()
                    ->readOnly(),
                Select::make('kategori')
                    ->options([
                        'KBM' => 'KBM',
                        'Kegiatan Madrasah' => 'Kegiatan Madrasah',
                        'Asesmen/Penilaian' => 'Asesmen/Penilaian',
                        'Pelaksanaan ATS Ganjil/Genap' => 'Pelaksanaan ATS Ganjil/Genap',
                        'Pelaksanaan AAS Ganjil/Genap' => 'Pelaksanaan AAS Ganjil/Genap',
                        'Pembagian Raport PTS Ganjil/Genap' => 'Pembagian Raport PTS Ganjil/Genap',
                        'Pembagian Raport AAS Ganjil/Genap' => 'Pembagian Raport AAS Ganjil/Genap',
                        'Hari Libur' => 'Hari Libur',
                    ])
                    ->required()
                    ->searchable(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
