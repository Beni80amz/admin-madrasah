<?php

namespace App\Filament\Resources\SiswaMasuks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\SiswaMasuk;

class SiswaMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Sekolah Asal')
                    ->schema([
                        TextInput::make('sekolah_asal')
                            ->label('Sekolah Asal')
                            ->placeholder('Contoh: SDN 01 Bandung')
                            ->required(),

                        TextInput::make('kelas_asal')
                            ->label('Kelas Asal')
                            ->placeholder('Contoh: 4-A'),

                        DatePicker::make('tanggal_masuk')
                            ->label('Tanggal Masuk')
                            ->required()
                            ->default(now()),

                        TextInput::make('nomor_surat_pindah')
                            ->label('Nomor Surat Pindah dari Sekolah Asal')
                            ->placeholder('Contoh: 001/SP/2024'),

                        Select::make('kelas_tujuan')
                            ->label('Kelas Tujuan di Madrasah')
                            ->options([
                                '1' => 'Kelas 1',
                                '2' => 'Kelas 2',
                                '3' => 'Kelas 3',
                                '4' => 'Kelas 4',
                                '5' => 'Kelas 5',
                                '6' => 'Kelas 6',
                            ])
                            ->required(),

                        Textarea::make('alasan_pindah')
                            ->label('Alasan Pindah')
                            ->placeholder('Contoh: Ikut orang tua pindah tugas')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Data Siswa')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto Siswa')
                            ->image()
                            ->imageEditor()
                            ->directory('siswa-masuk')
                            ->columnSpanFull(),

                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('nik')
                            ->label('NIK')
                            ->placeholder('16 digit NIK')
                            ->maxLength(16),

                        TextInput::make('nisn')
                            ->label('NISN')
                            ->placeholder('10 digit NISN')
                            ->maxLength(10),

                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),

                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->placeholder('Contoh: Jakarta'),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir'),
                    ])
                    ->columns(2),

                Section::make('Data Orang Tua & Kontak')
                    ->schema([
                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->placeholder('Nama lengkap ayah'),

                        TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->placeholder('Nama lengkap ibu'),

                        TextInput::make('nomor_mobile')
                            ->label('Nomor HP')
                            ->placeholder('08xxxxxxxxxx')
                            ->tel(),

                        Textarea::make('alamat_domisili')
                            ->label('Alamat Domisili')
                            ->placeholder('Alamat lengkap tempat tinggal')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Status Verifikasi')
                    ->schema([
                        Placeholder::make('nomor_surat_penerimaan_display')
                            ->label('Nomor Surat Penerimaan')
                            ->content(fn(?SiswaMasuk $record): string => $record?->nomor_surat_penerimaan ?? 'Akan di-generate otomatis'),

                        Placeholder::make('status_display')
                            ->label('Status')
                            ->content(fn(?SiswaMasuk $record): string => $record ? SiswaMasuk::getStatusOptions()[$record->status] : 'Pending'),

                        Placeholder::make('verified_at_display')
                            ->label('Waktu Verifikasi')
                            ->content(fn(?SiswaMasuk $record): string => $record?->verified_at?->format('d/m/Y H:i') ?? '-')
                            ->visible(fn(?SiswaMasuk $record): bool => $record?->verified_at !== null),

                        Placeholder::make('catatan_verifikasi_display')
                            ->label('Catatan Verifikasi')
                            ->content(fn(?SiswaMasuk $record): string => $record?->catatan_verifikasi ?? '-')
                            ->visible(fn(?SiswaMasuk $record): bool => !empty($record?->catatan_verifikasi))
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->visible(fn(?SiswaMasuk $record): bool => $record !== null),
            ]);
    }
}
