<?php

namespace App\Filament\Resources\SiswaKeluars\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiswaKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pribadi')
                    ->schema([
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('nis_lokal')
                            ->label('NIS Lokal')
                            ->required(),

                        TextInput::make('nisn')
                            ->label('NISN')
                            ->required(),

                        TextInput::make('nik')
                            ->label('NIK')
                            ->maxLength(16),

                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('kelas_terakhir')
                            ->label('Kelas Terakhir')
                            ->required(),

                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
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
                            ->required(),

                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->required(),

                        TextInput::make('nomor_mobile')
                            ->label('Nomor Mobile/HP')
                            ->tel()
                            ->maxLength(15),
                    ])
                    ->columns(2),

                Section::make('Alamat')
                    ->schema([
                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Informasi Keluar & Surat Keterangan')
                    ->schema([
                        DatePicker::make('tanggal_keluar')
                            ->label('Tanggal Keluar')
                            ->required(),

                        TextInput::make('sekolah_tujuan')
                            ->label('Sekolah Tujuan')
                            ->placeholder('Nama sekolah tujuan'),

                        TextInput::make('nomor_surat')
                            ->label('Nomor Surat Keterangan')
                            ->placeholder('Contoh: B.078/MI.AMZ-116/SKPM/08/2025')
                            ->helperText('Nomor surat untuk Surat Keterangan Pindah/Keluar'),

                        TextInput::make('nomor_dokumen_emis')
                            ->label('Nomor Dokumen EMIS')
                            ->placeholder('Nomor dokumen dari sistem EMIS'),

                        Textarea::make('alasan_keluar')
                            ->label('Alasan Keluar')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Foto')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->directory('siswa-keluar')
                            ->disk('public')
                            ->imageEditor()
                            ->circleCropper(),
                    ]),
            ]);
    }
}
