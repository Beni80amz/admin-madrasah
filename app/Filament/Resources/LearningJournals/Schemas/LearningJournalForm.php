<?php

namespace App\Filament\Resources\LearningJournals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Log;

class LearningJournalForm
{
    public static function configure(Schema $schema): Schema
    {
        Log::info('Configuring LearningJournalForm - V3 Force Single Column');

        return $schema
            ->columns(1) // STRICT SINGLE COLUMN FOR ENTIRE PAGE
            ->components([
                View::make('filament.resources.learning-journals.header-instructions'),

                // 1. Data Administrasi
                Section::make('Data Administrasi')
                    ->description('Informasi dasar terkait pelaksanaan pembelajaran.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Grid::make(3) // Inner grid for field distribution
                            ->schema([
                                Select::make('user_id')
                                    ->label('Guru Pengampu')
                                    ->prefixIcon('heroicon-m-user')
                                    ->options(\App\Models\Teacher::whereNotNull('user_id')->pluck('nama_lengkap', 'user_id'))
                                    ->searchable()
                                    ->preload()
                                    ->default(Auth::id())
                                    ->disabled(fn() => !Auth::user()->hasRole(['Superadmin', 'super_admin']))
                                    ->dehydrated()
                                    ->required()
                                    ->columnSpan(2), // ENLARGED: Spans 2 out of 3 columns

                                DatePicker::make('date')
                                    ->label('Tanggal Pelaksanaan')
                                    ->prefixIcon('heroicon-m-calendar-days')
                                    ->default(now())
                                    ->required()
                                    ->columnSpan(1),

                                Select::make('semester')
                                    ->label('Semester')
                                    ->prefixIcon('heroicon-m-clock')
                                    ->options([
                                        'Ganjil' => 'Ganjil',
                                        'Genap' => 'Genap',
                                    ])
                                    ->required()
                                    ->columnSpan(1),

                                Select::make('mata_pelajaran_id')
                                    ->label('Mata Pelajaran')
                                    ->prefixIcon('heroicon-m-book-open')
                                    ->relationship('mataPelajaran', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('pertemuan_ke')
                                    ->label('Pertemuan Ke-')
                                    ->prefixIcon('heroicon-m-hashtag')
                                    ->placeholder('Contoh: 1')
                                    ->required()
                                    ->columnSpan(1),

                                Select::make('rombel_id')
                                    ->label('Kelas / Rombel')
                                    ->prefixIcon('heroicon-m-user-group')
                                    ->options(function () {
                                        $user = Auth::user();
                                        if (!$user || !$user->teacher) {
                                            return \App\Models\Rombel::all()->pluck('nama_lengkap', 'id');
                                        }

                                        $teacher = $user->teacher;
                                        $isGuruKelas = $teacher->jabatan?->nama === 'Guru Kelas' || \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->exists();

                                        if ($isGuruKelas) {
                                            $rombelId = $teacher->rombel_id ?? \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->value('id');
                                            return \App\Models\Rombel::where('id', $rombelId)->get()->pluck('nama_lengkap', 'id');
                                        }

                                        // Guru Mata Pelajaran: Options from their schedule
                                        $rombelIds = \App\Models\JadwalPelajaran::where('teacher_id', $teacher->id)
                                            ->pluck('rombel_id');

                                        return \App\Models\Rombel::whereIn('id', $rombelIds)->get()->pluck('nama_lengkap', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->default(function () {
                                        $user = Auth::user();
                                        if ($user && $user->teacher) {
                                            $teacher = $user->teacher;
                                            $isGuruKelas = $teacher->jabatan?->nama === 'Guru Kelas' || \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->exists();

                                            if ($isGuruKelas) {
                                                return $teacher->rombel_id ?? \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->value('id');
                                            }
                                        }
                                        return null;
                                    })
                                    ->required()
                                    ->columnSpan(2),
                            ]),
                    ]),

                // 2. Jurnal Kegiatan
                Section::make('Jurnal Kegiatan')
                    ->description('Deskripsi materi dan aktivitas yang dilakukan.')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        Textarea::make('materi')
                            ->label('Materi Pembelajaran / Kompetensi Dasar')
                            ->placeholder('Jelaskan materi yang dibahas dan metode yang digunakan...')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                // 3. Presensi
                Section::make('Presensi Peserta Didik')
                    ->description('Catat kehadiran siswa secara detail.')
                    ->icon('heroicon-o-users')
                    ->collapsible()
                    ->schema([
                        Hidden::make('absensi_s')->default(0),
                        Hidden::make('absensi_i')->default(0),
                        Hidden::make('absensi_a')->default(0),

                        Grid::make(1)
                            ->visible(fn($get) => $get('rombel_id'))
                            ->schema([
                                Select::make('students_sakit')
                                    ->label('Sakit (S)')
                                    ->multiple()
                                    ->options(fn($get) => LearningJournalForm::getStudentOptions($get('rombel_id')))
                                    ->preload()
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(fn($state, $set) => $set('absensi_s', count($state ?? [])))
                                    ->prefixIcon('heroicon-m-heart')
                                    ->placeholder('Pilih siswa sakit...'),
                                Select::make('students_izin')
                                    ->label('Izin (I)')
                                    ->multiple()
                                    ->options(fn($get) => LearningJournalForm::getStudentOptions($get('rombel_id')))
                                    ->preload()
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(fn($state, $set) => $set('absensi_i', count($state ?? [])))
                                    ->prefixIcon('heroicon-m-hand-raised')
                                    ->placeholder('Pilih siswa izin...'),
                                Select::make('students_alpha')
                                    ->label('Alpha (A)')
                                    ->multiple()
                                    ->options(fn($get) => LearningJournalForm::getStudentOptions($get('rombel_id')))
                                    ->preload()
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(fn($state, $set) => $set('absensi_a', count($state ?? [])))
                                    ->prefixIcon('heroicon-m-x-circle')
                                    ->placeholder('Pilih siswa alpha...'),
                            ]),
                    ]),

                // 4. Refleksi
                Section::make('Refleksi & Evaluasi')
                    ->description('Evaluasi pelaksanaan untuk perbaikan.')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Textarea::make('hambatan')
                                    ->label('Hambatan / Catatan Khusus')
                                    ->placeholder('Tuliskan hambatan yang dialami...')
                                    ->rows(4),
                                Textarea::make('solusi')
                                    ->label('Solusi / Tindak Lanjut')
                                    ->placeholder('Tuliskan solusi yang dilakukan...')
                                    ->rows(4),
                            ]),
                    ]),
            ]);
    }

    public static function getStudentOptions($rombelId): array
    {
        if (!$rombelId) {
            return [];
        }

        try {
            $rombel = \App\Models\Rombel::with('kelas')->find($rombelId);
            if (!$rombel) {
                return [];
            }

            $tingkat = self::romanToArabic((string) ($rombel->kelas?->tingkat ?? ''));
            $kelasString = $tingkat . '-' . ($rombel->nama ?? '');

            return \App\Models\Student::where('kelas', $kelasString)
                ->where('is_active', true)
                ->pluck('nama_lengkap', 'id')
                ->toArray();
        } catch (\Exception $e) {
            Log::error("Error in getStudentOptions: " . $e->getMessage());
            return [];
        }
    }

    public static function romanToArabic(?string $roman): string
    {
        if (is_null($roman) || $roman === '') {
            return '';
        }

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
    }
}
