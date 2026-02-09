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
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Informasi !')
                            ->columnSpan(2)
                            ->icon('heroicon-o-information-circle')
                            ->color('info')
                            ->schema([
                                \Filament\Forms\Components\Placeholder::make('info_text')
                                    ->hiddenLabel()
                                    ->content('Jurnal Pembelajaran adalah dokumen yang bersifat retrospektif (melihat ke belakang) dan reflektif. Dokumen ini mencatat apa yang sebenarnya terjadi di dalam kelas selama proses pembelajaran berlangsung. Fokus utamanya adalah dokumentasi pelaksanaan dan evaluasi spontan.'),
                            ]),
                        Section::make('Petunjuk Pengisian')
                            ->columnSpan(1)
                            ->icon('heroicon-o-book-open')
                            ->color('success')
                            ->schema([
                                \Filament\Forms\Components\Placeholder::make('instruction_text')
                                    ->hiddenLabel()
                                    ->content(new \Illuminate\Support\HtmlString('
                                        <ol style="list-style-type: decimal; padding-left: 1rem;">
                                            <li><strong>Jurnal Pembelajaran:</strong> Isi segera setelah keluar dari kelas agar detail kejadian, respon siswa, dan kendala teknis tidak terlupakan.</li>
                                            <li><strong>Kolom Absensi:</strong> Diisi dengan jumlah siswa yang tidak hadir (S: Sakit, I: Izin, A: Alpha).</li>
                                            <li><strong>Hambatan & Solusi:</strong> Bagian ini sangat penting untuk akreditasi dan supervisi karena menunjukkan proses refleksi guru.</li>
                                        </ol>
                                    ')),
                            ]),
                    ]),
                Section::make('Informasi Pelajaran')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Guru')
                                    ->options(\App\Models\Teacher::whereNotNull('user_id')->pluck('nama_lengkap', 'user_id'))
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
                                    ->live()
                                    ->default(function () {
                                        $user = Auth::user();
                                        if ($user && $user->teacher) {
                                            $rombel = \App\Models\Rombel::where('wali_kelas_id', $user->teacher->id)->first();
                                            return $rombel ? $rombel->id : null;
                                        }
                                        return null;
                                    })
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

                        Grid::make(3)
                            ->visible(fn(Get $get) => $get('rombel_id'))
                            ->schema([
                                Select::make('students_sakit')
                                    ->label('Siswa Sakit')
                                    ->multiple()
                                    ->options(function (Get $get) {
                                        return self::getStudentOptions($get('rombel_id'));
                                    })
                                    ->preload()
                                    ->searchable(),
                                Select::make('students_izin')
                                    ->label('Siswa Izin')
                                    ->multiple()
                                    ->options(function (Get $get) {
                                        return self::getStudentOptions($get('rombel_id'));
                                    })
                                    ->preload()
                                    ->searchable(),
                                Select::make('students_alpha')
                                    ->label('Siswa Alpha')
                                    ->multiple()
                                    ->options(function (Get $get) {
                                        return self::getStudentOptions($get('rombel_id'));
                                    })
                                    ->preload()
                                    ->searchable(),
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
