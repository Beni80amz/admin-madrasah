<?php

namespace App\Filament\Resources\LearningJournals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
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
                // Responsive Grid: 1 col on mobile, 3 cols ONLY on Large screens
                Grid::make(['default' => 1, 'lg' => 3])
                    ->schema([
                        // -- LEFT: Main Content (Span 2 on Large)
                        Group::make()
                            ->columnSpan(['lg' => 2])
                            ->schema([
                                // 1. Data Administrasi
                                Section::make('Data Administrasi')
                                    ->description('Informasi dasar terkait pelaksanaan pembelajaran.')
                                    ->icon('heroicon-o-clipboard-document-list')
                                    ->schema([
                                        // Inner Grid: 1 col on mobile, 2 cols on Medium+
                                        Grid::make(['default' => 1, 'md' => 2])
                                            ->schema([
                                                Select::make('user_id')
                                                    ->label('Guru Pengampu')
                                                    ->prefixIcon('heroicon-m-user')
                                                    ->options(\App\Models\Teacher::whereNotNull('user_id')->pluck('nama_lengkap', 'user_id'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->default(Auth::id())
                                                    ->disabled(fn() => !Auth::user()->hasRole(['Superadmin', 'super_admin']))
                                                    ->required()
                                                    ->columnSpan(1),
                                                DatePicker::make('date')
                                                    ->label('Tanggal Pelaksanaan')
                                                    ->prefixIcon('heroicon-m-calendar-days')
                                                    ->default(now())
                                                    ->required()
                                                    ->columnSpan(1),

                                                Select::make('mata_pelajaran_id')
                                                    ->label('Mata Pelajaran')
                                                    ->prefixIcon('heroicon-m-book-open')
                                                    ->relationship('mataPelajaran', 'nama')
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->columnSpanFull(),

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

                                // 2. Jurnal Kegiatan
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

                                // 3. Presensi
                                Section::make('Presensi Peserta Didik')
                                    ->description('Catat kehadiran siswa secara detail.')
                                    ->icon('heroicon-o-users')
                                    ->collapsible()
                                    ->schema([
                                        // Inner Grid for Absensi Inputs: 1 col mobile, 3 cols Medium+
                                        Grid::make(['default' => 1, 'md' => 3])
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
                                    ->description('Evaluasi pelaksanaan untuk perbaikan.')
                                    ->icon('heroicon-o-arrow-path-rounded-square')
                                    ->collapsible()
                                    ->collapsed()
                                    ->schema([
                                        Grid::make(1) // Keep reflection stacking for better writing space
                                            ->schema([
                                                Textarea::make('hambatan')
                                                    ->label('Hambatan / Catatan Khusus')
                                                    ->placeholder('Contoh: Siswa kurang fokus...')
                                                    ->rows(3),
                                                Textarea::make('solusi')
                                                    ->label('Solusi / Tindak Lanjut')
                                                    ->placeholder('Contoh: Menggunakan media video...')
                                                    ->rows(3),
                                            ]),
                                    ]),
                            ]),

                        // -- RIGHT: Sidebar (Span 1 on Large)
                        Group::make()
                            ->columnSpan(['lg' => 1])
                            ->schema([
                                Section::make('Petunjuk Pengisian')
                                    ->icon('heroicon-o-information-circle')
                                    ->collapsible()
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('guidelines')
                                            ->hiddenLabel()
                                            ->content(new \Illuminate\Support\HtmlString('
                                                <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
                                                    <p>1. <strong>Isi Segera:</strong> Semakin cepat semakin baik agar data akurat.</p>
                                                    <p>2. <strong>Absensi:</strong> Pastikan jumlah angka sesuai dengan nama siswa yang dipilih.</p>
                                                    <p>3. <strong>Refleksi:</strong> Wajib diisi untuk keperluan supervisi dan akreditasi.</p>
                                                </div>
                                            ')),
                                    ]),

                                Section::make('Status Dokumen')
                                    ->icon('heroicon-o-document-check')
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('status_info')
                                            ->hiddenLabel()
                                            ->content('Dokumen ini adalah rekam jejak resmi aktivitas pembelajaran Anda.'),
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

            Log::info("Searching students for class: " . $kelasString);

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
