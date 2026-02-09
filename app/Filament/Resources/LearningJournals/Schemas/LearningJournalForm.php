<?php

namespace App\Filament\Resources\LearningJournals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class LearningJournalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pelajaran')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Guru')
                                    ->relationship('user', 'name')
                                    ->getOptionLabelFromRecordUsing(fn($record) => $record->teacher?->nama_lengkap ?? $record->name)
                                    ->searchable()
                                    ->preload()
                                    ->default(Auth::id())
                                    ->disabled(fn() => !Auth::user()->hasRole(['Superadmin', 'super_admin']))
                                    ->required(),
                                DatePicker::make('date')
                                    ->label('Tanggal')
                                    ->default(now())
                                    ->required(),
                                TextInput::make('pertemuan_ke')
                                    ->label('Pertemuan Ke-')
                                    ->placeholder('Misal: 1 atau Ganjil 1')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('mata_pelajaran_id')
                                    ->label('Mata Pelajaran')
                                    ->relationship('mataPelajaran', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('rombel_id')
                                    ->label('Kelas / Rombel')
                                    ->relationship('rombel', 'nama')
                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->kelas?->nama} - {$record->nama}")
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                    ]),

                Section::make('Pelaksanaan Pembelajaran')
                    ->schema([
                        Textarea::make('materi')
                            ->label('Materi / ATP')
                            ->placeholder('Tuliskan materi yang dibahas hari ini...')
                            ->rows(3)
                            ->required(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('absensi_s')
                                    ->label('Sakit (S)')
                                    ->numeric()
                                    ->default(0),
                                TextInput::make('absensi_i')
                                    ->label('Izin (I)')
                                    ->numeric()
                                    ->default(0),
                                TextInput::make('absensi_a')
                                    ->label('Alpha (A)')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),

                Section::make('Refleksi & Evaluasi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Textarea::make('hambatan')
                                    ->label('Hambatan & Catatan Kelas')
                                    ->placeholder('Misal: Siswa sulit memahami konsep X, ada kendala proyektor...')
                                    ->rows(4),
                                Textarea::make('solusi')
                                    ->label('Solusi / Tindak Lanjut')
                                    ->placeholder('Misal: Mengulangi materi di pertemuan depan dengan metode demonstrasi...')
                                    ->rows(4),
                            ]),
                    ]),
            ]);
    }
}
