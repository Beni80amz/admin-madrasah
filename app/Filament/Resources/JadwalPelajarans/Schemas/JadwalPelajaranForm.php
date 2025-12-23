<?php

namespace App\Filament\Resources\JadwalPelajarans\Schemas;

use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use App\Models\Teacher;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JadwalPelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        // Helper to convert Roman numerals to Arabic
        $romanToArabic = function ($roman) {
            $romans = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100];
            $roman = strtoupper(trim($roman));
            if (is_numeric($roman)) {
                return $roman;
            }
            $result = 0;
            $length = strlen($roman);
            for ($i = 0; $i < $length; $i++) {
                $current = $romans[$roman[$i]] ?? 0;
                $next = ($i + 1 < $length) ? ($romans[$roman[$i + 1]] ?? 0) : 0;
                if ($current < $next) {
                    $result -= $current;
                } else {
                    $result += $current;
                }
            }
            return $result > 0 ? (string) $result : $roman;
        };

        return $schema
            ->components([
                Section::make('Informasi Jadwal')
                    ->schema([
                        Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran')
                            ->options(TahunAjaran::pluck('nama', 'id'))
                            ->default(fn() => TahunAjaran::where('is_active', true)->first()?->id)
                            ->required()
                            ->native(false)
                            ->searchable(),

                        Select::make('semester')
                            ->label('Semester')
                            ->options(JadwalPelajaran::getSemesterOptions())
                            ->required()
                            ->native(false),

                        Select::make('rombel_id')
                            ->label('Rombel/Kelas')
                            ->options(function () use ($romanToArabic) {
                                return Rombel::with('kelas')
                                    ->get()
                                    ->mapWithKeys(function ($rombel) use ($romanToArabic) {
                                        $tingkat = $romanToArabic($rombel->kelas?->tingkat ?? '');
                                        $label = ($rombel->kelas?->nama ?? '') . ' - ' . $rombel->nama;
                                        return [$rombel->id => $label];
                                    })
                                    ->toArray();
                            })
                            ->required()
                            ->native(false)
                            ->searchable(),

                        Select::make('mata_pelajaran_id')
                            ->label('Mata Pelajaran')
                            ->options(MataPelajaran::where('is_active', true)->pluck('nama', 'id'))
                            ->required()
                            ->native(false)
                            ->searchable(),

                        Select::make('teacher_id')
                            ->label('Guru Pengajar')
                            ->options(Teacher::where('is_active', true)->pluck('nama_lengkap', 'id'))
                            ->required()
                            ->native(false)
                            ->searchable(),
                    ])
                    ->columns(2),

                Section::make('Waktu Pelajaran')
                    ->schema([
                        Select::make('hari')
                            ->label('Hari')
                            ->options(JadwalPelajaran::getHariOptions())
                            ->required()
                            ->native(false),

                        Select::make('jam_ke')
                            ->label('Jam Ke')
                            ->options(JadwalPelajaran::getJamKeOptions())
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Auto-fill jam_mulai and jam_selesai based on jam_ke
                                $jadwalJam = [
                                    1 => ['07:00', '07:35'],
                                    2 => ['07:35', '08:10'],
                                    3 => ['08:10', '08:45'],
                                    4 => ['08:45', '09:20'],
                                    5 => ['09:35', '10:10'],
                                    6 => ['10:10', '10:45'],
                                    7 => ['10:45', '11:20'],
                                    8 => ['11:20', '11:55'],
                                ];

                                if (isset($jadwalJam[$state])) {
                                    $set('jam_mulai', $jadwalJam[$state][0]);
                                    $set('jam_selesai', $jadwalJam[$state][1]);
                                }
                            }),

                        TimePicker::make('jam_mulai')
                            ->label('Jam Mulai')
                            ->required()
                            ->seconds(false),

                        TimePicker::make('jam_selesai')
                            ->label('Jam Selesai')
                            ->required()
                            ->seconds(false),
                    ])
                    ->columns(4),

                Section::make('Keterangan')
                    ->schema([
                        Textarea::make('keterangan')
                            ->label('Catatan')
                            ->placeholder('Catatan tambahan (opsional)')
                            ->rows(2)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
