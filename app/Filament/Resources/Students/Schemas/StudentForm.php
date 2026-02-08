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

                Section::make('Data Kartu Keluarga')
                    ->schema([
                        TextInput::make('no_kk')
                            ->label('Nomor KK')
                            ->placeholder('Masukan 16 digit No. KK')
                            ->maxLength(16),
                        TextInput::make('nama_kepala_keluarga_diKK')
                            ->label('Nama Kepala Keluarga (di KK)')
                            ->placeholder('Contoh: Budi Rahman'),
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

                Section::make('Data Ayah Kandung')
                    ->schema([
                        TextInput::make('nama_ayah')
                            ->label('Nama Lengkap Ayah')
                            ->placeholder('Contoh: Budi Rahman')
                            ->required()
                            ->disabled(fn($get) => $get('status_ayah') === 'Meninggal'),
                        Select::make('status_ayah')
                            ->label('Status Ayah')
                            ->options([
                                'Masih Hidup' => 'Masih Hidup',
                                'Meninggal' => 'Meninggal',
                            ])
                            ->native(false)
                            ->live(),
                        TextInput::make('nik_ayah_kandung')
                            ->label('NIK Ayah')
                            ->placeholder('16 digit NIK')
                            ->maxLength(16)
                            ->disabled(fn($get) => $get('status_ayah') === 'Meninggal'),
                        TextInput::make('tempat_lahir_ayah_kandung')
                            ->label('Tempat Lahir Ayah')
                            ->disabled(fn($get) => $get('status_ayah') === 'Meninggal'),
                        DatePicker::make('tgl_lahir_ayah_kandung')
                            ->label('Tanggal Lahir Ayah')
                            ->disabled(fn($get) => $get('status_ayah') === 'Meninggal'),
                        Select::make('pendidikan_ayah_kandung')
                            ->label('Pendidikan Terakhir Ayah')
                            ->options([
                                'Lulus SD/Sederajat' => 'Lulus SD/Sederajat',
                                'Lulus SMP/Sederajat' => 'Lulus SMP/Sederajat',
                                'Lulus SMA/MA/Sederajat' => 'Lulus SMA/MA/Sederajat',
                                'Lulus D1' => 'Lulus D1',
                                'Lulus D2' => 'Lulus D2',
                                'Lulus D3' => 'Lulus D3',
                                'Lulus S1' => 'Lulus S1',
                                'Lulus S2' => 'Lulus S2',
                                'Lulus S3' => 'Lulus S3',
                            ])
                            ->native(false)
                            ->disabled(fn($get) => $get('status_ayah') === 'Meninggal'),
                        Select::make('pekerjaan_ayah_kandung')
                            ->label('Pekerjaan Ayah')
                            ->options([
                                'Tidak Bekerja' => 'Tidak Bekerja',
                                'Pensiunan' => 'Pensiunan',
                                'PNS' => 'PNS',
                                'TNI/Polri' => 'TNI/Polri',
                                'Guru/Dosen' => 'Guru/Dosen',
                                'Pegawai Swasta' => 'Pegawai Swasta',
                                'Wiraswasta' => 'Wiraswasta',
                                'Pengacara/Notaris' => 'Pengacara/Notaris',
                                'Dokter/Bidan/Perawat' => 'Dokter/Bidan/Perawat',
                                'Petani/Nelayan' => 'Petani/Nelayan',
                                'Buruh (Pabrik/Bangunan)' => 'Buruh (Pabrik/Bangunan)',
                                'Sopir/Kondektur/Gojek' => 'Sopir/Kondektur/Gojek',
                                'Politikus' => 'Politikus',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->native(false)
                            ->live()
                            ->disabled(fn($get) => $get('status_ayah') === 'Meninggal'),
                        TextInput::make('pekerjaan_ayah_kandung_lainnya')
                            ->label('Pekerjaan Ayah Lainnya')
                            ->visible(fn($get) => $get('pekerjaan_ayah_kandung') === 'Lainnya')
                            ->required(fn($get) => $get('pekerjaan_ayah_kandung') === 'Lainnya'),
                    ])
                    ->columns(2),

                Section::make('Data Ibu Kandung')
                    ->schema([
                        TextInput::make('nama_ibu')
                            ->label('Nama Lengkap Ibu')
                            ->placeholder('Contoh: Siti Aminah')
                            ->required()
                            ->disabled(fn($get) => $get('status_ibu') === 'Meninggal'),
                        Select::make('status_ibu')
                            ->label('Status Ibu')
                            ->options([
                                'Masih Hidup' => 'Masih Hidup',
                                'Meninggal' => 'Meninggal',
                            ])
                            ->native(false)
                            ->live(),
                        TextInput::make('nik_ibu')
                            ->label('NIK Ibu')
                            ->placeholder('16 digit NIK')
                            ->maxLength(16)
                            ->disabled(fn($get) => $get('status_ibu') === 'Meninggal'),
                        TextInput::make('tempat_lahir_ibu')
                            ->label('Tempat Lahir Ibu')
                            ->disabled(fn($get) => $get('status_ibu') === 'Meninggal'),
                        DatePicker::make('tanggal_lahir_ibu')
                            ->label('Tanggal Lahir Ibu')
                            ->disabled(fn($get) => $get('status_ibu') === 'Meninggal'),
                        Select::make('pendidikan_ibu')
                            ->label('Pendidikan Terakhir Ibu')
                            ->options([
                                'Lulus SD/Sederajat' => 'Lulus SD/Sederajat',
                                'Lulus SMP/Sederajat' => 'Lulus SMP/Sederajat',
                                'Lulus SMA/MA/Sederajat' => 'Lulus SMA/MA/Sederajat',
                                'Lulus D1' => 'Lulus D1',
                                'Lulus D2' => 'Lulus D2',
                                'Lulus D3' => 'Lulus D3',
                                'Lulus S1' => 'Lulus S1',
                                'Lulus S2' => 'Lulus S2',
                                'Lulus S3' => 'Lulus S3',
                            ])
                            ->native(false)
                            ->disabled(fn($get) => $get('status_ibu') === 'Meninggal'),
                        Select::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu')
                            ->options([
                                'Tidak Bekerja' => 'Tidak Bekerja',
                                'Pensiunan' => 'Pensiunan',
                                'PNS' => 'PNS',
                                'TNI/Polri' => 'TNI/Polri',
                                'Guru/Dosen' => 'Guru/Dosen',
                                'Pegawai Swasta' => 'Pegawai Swasta',
                                'Wiraswasta' => 'Wiraswasta',
                                'Pengacara/Notaris' => 'Pengacara/Notaris',
                                'Dokter/Bidan/Perawat' => 'Dokter/Bidan/Perawat',
                                'Petani/Nelayan' => 'Petani/Nelayan',
                                'Buruh (Pabrik/Bangunan)' => 'Buruh (Pabrik/Bangunan)',
                                'Sopir/Kondektur/Gojek' => 'Sopir/Kondektur/Gojek',
                                'Politikus' => 'Politikus',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->native(false)
                            ->live()
                            ->disabled(fn($get) => $get('status_ibu') === 'Meninggal'),
                        TextInput::make('pekerjaan_ibu_lainnya')
                            ->label('Pekerjaan Ibu Lainnya')
                            ->visible(fn($get) => $get('pekerjaan_ibu') === 'Lainnya')
                            ->required(fn($get) => $get('pekerjaan_ibu') === 'Lainnya'),
                    ])
                    ->columns(2),

                Section::make('Data Wali (Opsional)')
                    ->schema([
                        TextInput::make('nik_wali')
                            ->label('NIK Wali')
                            ->placeholder('16 digit NIK')
                            ->maxLength(16),
                        TextInput::make('tempat_lahir_wali')
                            ->label('Tempat Lahir Wali'),
                        DatePicker::make('tanggal_lahir_wali')
                            ->label('Tanggal Lahir Wali'),
                        Select::make('pendidikan_wali')
                            ->label('Pendidikan Terakhir Wali')
                            ->options([
                                'Lulus SD/Sederajat' => 'Lulus SD/Sederajat',
                                'Lulus SMP/Sederajat' => 'Lulus SMP/Sederajat',
                                'Lulus SMA/MA/Sederajat' => 'Lulus SMA/MA/Sederajat',
                                'Lulus D1' => 'Lulus D1',
                                'Lulus D2' => 'Lulus D2',
                                'Lulus D3' => 'Lulus D3',
                                'Lulus S1' => 'Lulus S1',
                                'Lulus S2' => 'Lulus S2',
                                'Lulus S3' => 'Lulus S3',
                            ])
                            ->native(false),
                        Select::make('pekerjaan_wali')
                            ->label('Pekerjaan Wali')
                            ->options([
                                'Tidak Bekerja' => 'Tidak Bekerja',
                                'Pensiunan' => 'Pensiunan',
                                'PNS' => 'PNS',
                                'TNI/Polri' => 'TNI/Polri',
                                'Guru/Dosen' => 'Guru/Dosen',
                                'Pegawai Swasta' => 'Pegawai Swasta',
                                'Wiraswasta' => 'Wiraswasta',
                                'Pengacara/Notaris' => 'Pengacara/Notaris',
                                'Dokter/Bidan/Perawat' => 'Dokter/Bidan/Perawat',
                                'Petani/Nelayan' => 'Petani/Nelayan',
                                'Buruh (Pabrik/Bangunan)' => 'Buruh (Pabrik/Bangunan)',
                                'Sopir/Kondektur/Gojek' => 'Sopir/Kondektur/Gojek',
                                'Politikus' => 'Politikus',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->native(false)
                            ->live(),
                        TextInput::make('pekerjaan_wali_lainnya')
                            ->label('Pekerjaan Wali Lainnya')
                            ->visible(fn($get) => $get('pekerjaan_wali') === 'Lainnya')
                            ->required(fn($get) => $get('pekerjaan_wali') === 'Lainnya'),
                    ])
                    ->columns(2),

                Section::make('Informasi Ekonomi & Domisili')
                    ->schema([
                        Select::make('penghasilan_orangtua')
                            ->label('Penghasilan Orang Tua')
                            ->options([
                                'Kurang dari Rp. 500.000' => 'Kurang dari Rp. 500.000',
                                'Rp. 500.000-Rp. 1.000.000' => 'Rp. 500.000-Rp. 1.000.000',
                                'Rp. 1.000.000-2.000.000' => 'Rp. 1.000.000-2.000.000',
                                'Rp. 2 Juta-4 Juta' => 'Rp. 2 Juta-4 Juta',
                                'Rp. 4 Juta-5 Juta' => 'Rp. 4 Juta-5 Juta',
                                'Lebih dari Rp. 5 Juta' => 'Lebih dari Rp. 5 Juta',
                            ])
                            ->native(false),
                        Select::make('status_rumah')
                            ->label('Status Rumah')
                            ->options([
                                'Rumah Sendiri' => 'Rumah Sendiri',
                                'Rumah Orang Tua' => 'Rumah Orang Tua',
                                'Sewa/Kontrak' => 'Sewa/Kontrak',
                                'Asrama/Rumah Dinas' => 'Asrama/Rumah Dinas',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->native(false)
                            ->live(),
                        TextInput::make('status_rumah_lainnya')
                            ->label('Status Rumah Lainnya')
                            ->visible(fn($get) => $get('status_rumah') === 'Lainnya')
                            ->required(fn($get) => $get('status_rumah') === 'Lainnya'),

                        TextInput::make('nomor_mobile')
                            ->label('Nomor Mobile/HP')
                            ->placeholder('Contoh: 081234567890')
                            ->tel()
                            ->maxLength(15),

                        TextInput::make('nomor_pip')
                            ->label('Nomor PIP')
                            ->placeholder('Contoh: 1234567890123456')
                            ->maxLength(20),

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
                    ])
                    ->columns(2),
            ]);
    }
}
