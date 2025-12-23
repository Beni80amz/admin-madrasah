<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Foto & Status')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->directory('students')
                            ->disk('public')
                            ->imageEditor()
                            ->circleCropper(),

                        Select::make('status')
                            ->label('Status Siswa')
                            ->options(\App\Models\Student::getFormStatusOptions())
                            ->default('aktif')
                            ->required()
                            ->native(false)
                            ->live()
                            ->helperText('Pilih status siswa. Jika Lulus atau Mutasi Keluar, data akan otomatis dipindahkan. Untuk Mutasi Masuk, gunakan menu Siswa Masuk.'),

                        TextInput::make('tahun_lulus')
                            ->label('Tahun Lulus')
                            ->placeholder('Contoh: 2024')
                            ->maxLength(4)
                            ->visible(fn($get) => $get('status') === 'lulus')
                            ->required(fn($get) => $get('status') === 'lulus'),

                        DatePicker::make('tanggal_keluar')
                            ->label('Tanggal Keluar')
                            ->visible(fn($get) => $get('status') === 'mutasi_keluar')
                            ->required(fn($get) => $get('status') === 'mutasi_keluar'),

                        Textarea::make('alasan_keluar')
                            ->label('Alasan Keluar')
                            ->placeholder('Contoh: Pindah domisili ke kota lain')
                            ->rows(2)
                            ->visible(fn($get) => $get('status') === 'mutasi_keluar'),

                        TextInput::make('sekolah_tujuan')
                            ->label('Sekolah Tujuan')
                            ->placeholder('Contoh: SDN 01 Jakarta')
                            ->visible(fn($get) => $get('status') === 'mutasi_keluar'),

                        TextInput::make('nomor_dokumen_emis')
                            ->label('Nomor Dokumen EMIS')
                            ->placeholder('Nomor dokumen dari sistem EMIS')
                            ->visible(fn($get) => $get('status') === 'mutasi_keluar'),
                    ])
                    ->columns(2),

                Section::make('Data Pribadi')
                    ->schema([
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->placeholder('Contoh: Ahmad Fauzi Rahman')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('nis_lokal')
                            ->label('NIS Lokal')
                            ->placeholder('Contoh: 2024001')
                            ->required(),

                        TextInput::make('nisn')
                            ->label('NISN')
                            ->placeholder('Contoh: 0051234567')
                            ->required(),

                        TextInput::make('nik')
                            ->label('NIK')
                            ->placeholder('Contoh: 3276051234560001')
                            ->maxLength(16)
                            ->required(),

                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required()
                            ->native(false),

                        Select::make('kelas')
                            ->label('Kelas')
                            ->options(function () {
                                // Helper to convert Roman numerals to Arabic
                                $romanToArabic = function ($roman) {
                                    $romans = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100];
                                    $roman = strtoupper(trim($roman));

                                    // If already numeric, return as is
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

                                return \App\Models\Rombel::with('kelas')
                                    ->get()
                                    ->mapWithKeys(function ($rombel) use ($romanToArabic) {
                                        // Get kelas tingkat and convert to numeric if Roman
                                        $tingkat = $romanToArabic($rombel->kelas?->tingkat ?? '');
                                        // Get rombel nama (e.g., "A")
                                        $rombelNama = $rombel->nama ?? '';
                                        // Value format: "6-A" (tingkat + "-" + nama)
                                        $value = $tingkat . '-' . $rombelNama;
                                        // Label format: "Kelas 6 - 6-A"
                                        $label = ($rombel->kelas?->nama ?? '') . ' - ' . $value;
                                        return [$value => $label];
                                    })
                                    ->sort()
                                    ->toArray();
                            })
                            ->searchable()
                            ->required()
                            ->native(false),

                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->placeholder('Contoh: Depok')
                            ->required(),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Data Orang Tua')
                    ->schema([
                        TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->placeholder('Contoh: Siti Aminah')
                            ->required(),

                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->placeholder('Contoh: Budi Rahman')
                            ->required(),

                        TextInput::make('nomor_mobile')
                            ->label('Nomor Mobile/HP')
                            ->placeholder('Contoh: 081234567890')
                            ->tel()
                            ->maxLength(15),

                        TextInput::make('nomor_pip')
                            ->label('Nomor PIP')
                            ->placeholder('Contoh: 1234567890123456')
                            ->maxLength(20),
                    ])
                    ->columns(2),

                Section::make('Alamat')
                    ->schema([
                        Textarea::make('alamat_kk')
                            ->label('Alamat KK')
                            ->placeholder('Contoh: Jl. Merdeka No. 10, Kota Depok')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('alamat_domisili')
                            ->label('Alamat Domisili')
                            ->placeholder('Kosongkan jika sama dengan alamat KK')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
