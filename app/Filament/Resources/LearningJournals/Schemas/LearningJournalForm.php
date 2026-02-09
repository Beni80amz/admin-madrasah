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
use Filament\Forms\Get;
use Illuminate\Support\Facades\Log;

class LearningJournalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(['default' => 1, 'sm' => 3, 'xl' => 3])
                    ->schema([
                        // -- LEFT: Main Form (2/3)
                        Grid::make(1)
                            ->columnSpan(['sm' => 2])
                            ->schema([
                                // 1. Informasi Pembelajaran
                                Section::make('Data Administrasi')
                                    ->description('Informasi dasar terkait pelaksanaan pembelajaran.')
                                    ->icon('heroicon-o-clipboard-document-list')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('user_id')
                                                    ->label('Guru Pengampu')
                                                    ->prefixIcon('heroicon-m-user')
                                                    ->options(\App\Models\Teacher::whereNotNull('user_id')->pluck('nama_lengkap', 'user_id'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->default(Auth::id())
                                                    ->disabled(fn() => !Auth::user()->hasRole(['Superadmin', 'super_admin']))
                                                    ->required(),
                                                DatePicker::make('date')
                                                    ->label('Tanggal Pelaksanaan')
                                                    ->prefixIcon('heroicon-m-calendar-days')
                                                    ->default(now())
                                                    ->required(),
                                            ]),
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('mata_pelajaran_id')
                                                    ->label('Mata Pelajaran')
                                                    ->prefixIcon('heroicon-m-book-open')
                                                    ->relationship('mataPelajaran', 'nama')
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->columnSpan(1),
                                                Select::make('rombel_id')
                                                    ->label('Kelas / Rombel')
                                                    ->prefixIcon('heroicon-m-user-group')
                                                    ->relationship('rombel', 'nama')
                                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->kelas?->nama} - {$record->nama}")
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->default(function () {
                                                        $user = Auth::user();
                                                        if ($user && $user->teacher) {
                                                            $rombel = \App\Models\Rombel::where('wali_kelas_id', $user->teacher->id)->first();
                                                            return $rombel ? $rombel->id : null;
                                                        }
                                                        return null;
                                                    })
                                                    ->required()
                                                    ->columnSpan(1),
                                                TextInput::make('pertemuan_ke')
                                                    ->label('Pertemuan Ke-')
                                                    ->prefixIcon('heroicon-m-hashtag')
                                                    ->placeholder('Contoh: 1')
                                                    ->required()
                                                    ->columnSpan(1),
                                            ]),
                                    ]),

                                // 2. Materi & Kegiatan
                                Section::make('Jurnal Kegiatan')
                                    ->description('Deskripsi materi dan aktivitas yang dilakukan.')
                                    ->icon('heroicon-o-pencil-square')
                                    ->schema([
                                        Textarea::make('materi')
                                            ->label('Materi Pembelajaran / Kompetensi Dasar')
                                            ->placeholder('Jelaskan materi yang dibahas dan metode yang digunakan...')
                                            ->rows(4)
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),

                                // 3. Presensi Siswa
                                Section::make('Presensi Peserta Didik')
                                    ->description('Catat kehadiran siswa secara detail.')
                                    ->icon('heroicon-o-users')
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('absensi_s')
                                                    ->label('Sakit (S)')
                                                    ->numeric()
                                                    ->prefixIcon('heroicon-m-heart')
                                                    ->default(0),
                                                TextInput::make('absensi_i')
                                                    ->label('Izin (I)')
                                                    ->numeric()
                                                    ->prefixIcon('heroicon-m-hand-raised')
                                                    ->default(0),
                                                TextInput::make('absensi_a')
                                                    ->label('Alpha (A)')
                                                    ->numeric()
                                                    ->prefixIcon('heroicon-m-x-circle')
                                                    ->default(0),
                                            ]),

                                        Grid::make(1)
                                            ->visible(fn($get) => $get('rombel_id'))
                                            ->schema([
                                                Select::make('students_sakit')
                                                    ->label('Daftar Siswa Sakit')
                                                    ->multiple()
                                                    ->options(fn($get) => LearningJournalForm::getStudentOptions($get('rombel_id')))
                                                    ->preload()
                                                    ->searchable()
                                                    ->placeholder('Pilih siswa yang sakit...'),
                                                Select::make('students_izin')
                                                    ->label('Daftar Siswa Izin')
                                                    ->multiple()
                                                    ->options(fn($get) => LearningJournalForm::getStudentOptions($get('rombel_id')))
                                                    ->preload()
                                                    ->searchable()
                                                    ->placeholder('Pilih siswa yang izin...'),
                                                Select::make('students_alpha')
                                                    ->label('Daftar Siswa Alpha')
                                                    ->multiple()
                                                    ->options(fn($get) => LearningJournalForm::getStudentOptions($get('rombel_id')))
                                                    ->preload()
                                                    ->searchable()
                                                    ->placeholder('Pilih siswa alpha...'),
                                            ]),
                                    ]),

                                // 4. Refleksi
                                Section::make('Refleksi & Evaluasi')
                                    ->description('Evaluasi pelaksanaan pembelajaran untuk perbaikan.')
                                    ->icon('heroicon-o-arrow-path-rounded-square')
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Textarea::make('hambatan')
                                                    ->label('Hambatan / Catatan Khusus')
                                                    ->placeholder('Contoh: Siswa kurang fokus saat diskusi kelompok...')
                                                    ->rows(4),
                                                Textarea::make('solusi')
                                                    ->label('Solusi / Tindak Lanjut')
                                                    ->placeholder('Contoh: Menggunakan media video agar lebih menarik...')
                                                    ->rows(4),
                                            ]),
                                    ]),
                            ]),

                        // -- RIGHT: Sidebar (Info & Petunjuk) (1/3)
                        Grid::make(1)
                            ->columnSpan(['sm' => 1])
                            ->schema([
                                Section::make('Petunjuk Pengisian')
                                    ->icon('heroicon-o-information-circle')
                                    ->collapsible()
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('guidelines')
                                            ->hiddenLabel()
                                            ->content(new \Illuminate\Support\HtmlString('
                                                <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
                                                    <div>
                                                        <strong>1. Jurnal Pembelajaran</strong>
                                                        <p class="mt-1">Isi jurnal segera setelah pembelajaran selesai agar detail kejadian masih segar dalam ingatan.</p>
                                                    </div>
                                                    <div>
                                                        <strong>2. Kolom Absensi</strong>
                                                        <p class="mt-1">Pastikan jumlah siswa (S/I/A) sesuai dengan daftar nama yang dipilih di bawahnya.</p>
                                                    </div>
                                                    <div>
                                                        <strong>3. Refleksi Guru</strong>
                                                        <p class="mt-1">Bagian ini penting untuk akreditasi. Tuliskan hambatan nyata dan solusi yang konkret.</p>
                                                    </div>
                                                </div>
                                            ')),
                                    ]),

                                Section::make('Penting !')
                                    ->icon('heroicon-o-exclamation-triangle')
                                    ->columns(1)
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('info_urgent')
                                            ->hiddenLabel()
                                            ->content('Dokumen ini bersifat resmi. Pastikan data yang diinputkan valid dan dapat dipertanggungjawabkan.'),
                                    ]),
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

            $tingkat = self::romanToArabic($rombel->kelas?->tingkat ?? '');
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

    public static function romanToArabic(string $roman): string
    {
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
