<?php

namespace App\Filament\Resources\StrukturOrganisasis\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StrukturOrganisasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('teacher_id')
                    ->label('Pilih Guru/Staff')
                    ->relationship('teacher', 'nama_lengkap')
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih guru (kosongkan jika anggota non-guru)')
                    ->helperText('Jika memilih guru, nama dan foto akan otomatis diambil dari data guru')
                    ->live()
                    ->columnSpanFull(),

                TextInput::make('nama')
                    ->label('Nama (Manual)')
                    ->placeholder('Contoh: H. Abdullah')
                    ->helperText('Diisi jika anggota bukan guru/staff (misal: Ketua Komite)')
                    ->visible(fn($get) => empty($get('teacher_id'))),

                FileUpload::make('photo')
                    ->label('Foto (Manual)')
                    ->image()
                    ->disk('public')
                    ->directory('struktur-organisasi')
                    ->visibility('public')
                    ->imageEditor()
                    ->helperText('Upload foto jika anggota bukan guru/staff')
                    ->visible(fn($get) => empty($get('teacher_id'))),

                Select::make('jabatan_struktural')
                    ->label('Jabatan Struktural')
                    ->options([
                        'Ketua Yayasan' => 'Ketua Yayasan',
                        'Kepala Madrasah' => 'Kepala Madrasah',
                        'Wakil Kepala Madrasah' => 'Wakil Kepala Madrasah',
                        'Ketua Komite' => 'Ketua Komite',
                        'Kepala TU' => 'Kepala TU',
                        'Staff TU' => 'Staff TU',
                        'Waka Kurikulum' => 'Waka Kurikulum',
                        'Waka Kesiswaan' => 'Waka Kesiswaan',
                        'Waka Humas' => 'Waka Humas',
                        'Waka Sarpras' => 'Waka Sarpras',
                        'Wali Kelas' => 'Wali Kelas',
                        'Bendahara' => 'Bendahara',
                        'Operator' => 'Operator',
                        'Koordinator BK' => 'Koordinator BK',
                        'Koordinator Ekstrakurikuler' => 'Koordinator Ekstrakurikuler',
                        'Pustakawan' => 'Pustakawan',
                        'Penjaga Sekolah' => 'Penjaga Sekolah',
                    ])
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Auto set level to 3 when Wali Kelas is selected
                        if ($state === 'Wali Kelas') {
                            $set('level', 4);
                        }
                    }),

                // Info Kelas/Rombel untuk Wali Kelas
                \Filament\Forms\Components\Placeholder::make('kelas_info')
                    ->label('Kelas/Rombel yang Diampu')
                    ->content(function ($get) {
                        $teacherId = $get('teacher_id');
                        $jabatan = $get('jabatan_struktural');

                        if ($jabatan === 'Wali Kelas' && $teacherId) {
                            $teacher = \App\Models\Teacher::with('rombelWaliKelas.kelas')->find($teacherId);
                            if ($teacher && $teacher->rombelWaliKelas) {
                                $rombel = $teacher->rombelWaliKelas;
                                return new \Illuminate\Support\HtmlString(
                                    '<span class="text-success-600 dark:text-success-400 font-medium">' .
                                    $rombel->kelas?->nama . ' - ' . $rombel->nama .
                                    '</span>'
                                );
                            }
                            return new \Illuminate\Support\HtmlString(
                                '<span class="text-warning-600 dark:text-warning-400">Guru ini belum menjadi wali kelas di tabel Rombel</span>'
                            );
                        }
                        return null;
                    })
                    ->visible(fn($get) => $get('jabatan_struktural') === 'Wali Kelas' && !empty($get('teacher_id'))),

                Select::make('level')
                    ->label('Level Hierarki')
                    ->options([
                        0 => 'Level 0 - Ketua Yayasan',
                        1 => 'Level 1 - Kepala Madrasah',
                        2 => 'Level 2 - Operator & Ketua Komite',
                        3 => 'Level 3 - Wakamad & Tata Usaha',
                        4 => 'Level 4 - Wali Kelas Bawah (1,2,3)',
                        5 => 'Level 5 - Wali Kelas Atas (4,5,6)',
                        6 => 'Level 6 - Bagian Umum',
                    ])
                    ->default(3)
                    ->required()
                    ->helperText('Level menentukan posisi vertikal dalam bagan'),

                TextInput::make('urutan')
                    ->label('Urutan dalam Level')
                    ->numeric()
                    ->default(0)
                    ->helperText('Urutan tampilan horizontal dalam level yang sama (0 = pertama)'),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText('Hanya anggota aktif yang ditampilkan di frontend'),
            ]);
    }
}

