<?php

namespace App\Filament\Resources\PpdbRegistrations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PpdbRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Informasi Pendaftar')
                            ->schema([
                                TextInput::make('no_daftar')
                                    ->label('No. Pendaftaran')
                                    ->readonly(), // Keep auto-generated field readonly
                                TextInput::make('nama_lengkap')
                                    ->label('Nama Lengkap')
                                    ->required(),
                                TextInput::make('nik')
                                    ->label('NIK')
                                    ->numeric()
                                    ->length(16)
                                    ->required(),
                                TextInput::make('nisn')
                                    ->label('NISN')
                                    ->numeric()
                                    ->maxLength(10),
                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->required(),
                                DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->required(),
                                Select::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->required(),
                                Select::make('agama')
                                    ->label('Agama')
                                    ->options([
                                        'Islam' => 'Islam',
                                        'Kristen' => 'Kristen',
                                        'Katolik' => 'Katolik',
                                        'Hindu' => 'Hindu',
                                        'Buddha' => 'Buddha',
                                        'Konghucu' => 'Konghucu',
                                    ])
                                    ->default('Islam')
                                    ->required(),
                                Textarea::make('alamat')
                                    ->label('Alamat Domisili')
                                    ->required(),
                                Textarea::make('alamat_kk')
                                    ->label('Alamat Sesuai KK')
                                    ->helperText('Jika alamat KK sama dengan alamat domisili, kosongkan field ini.'),
                                Select::make('asal_sekolah')
                                    ->label('Asal Sekolah')
                                    ->options([
                                        'TK' => 'TK',
                                        'RA' => 'RA',
                                        'PAUD' => 'PAUD',
                                        'BIMBA' => 'BIMBA',
                                        'Orang Tua' => 'Orang Tua',
                                        'Lainnya' => 'Lainnya',
                                    ])
                                    ->required()
                                    ->live(),
                                TextInput::make('nama_sekolah_asal')
                                    ->label('Nama Sekolah Asal')
                                    ->visible(fn(Get $get) => $get('asal_sekolah') !== 'Orang Tua' && filled($get('asal_sekolah')))
                                    ->required(fn(Get $get) => $get('asal_sekolah') !== 'Orang Tua' && filled($get('asal_sekolah'))),
                            ])->columns(2),

                        Section::make('Data Orang Tua')
                            ->schema([
                                TextInput::make('nama_ayah')
                                    ->label('Nama Ayah Kandung')
                                    ->required(),
                                TextInput::make('nama_ibu')
                                    ->label('Nama Ibu Kandung')
                                    ->required(),
                                TextInput::make('no_hp_ortu')
                                    ->label('No. HP Ortu')
                                    ->tel()
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Nama Wali (Jika ada)'),
                            ])->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Status & Verifikasi')
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'new' => 'Baru',
                                        'verified' => 'Terverifikasi',
                                        'rejected' => 'Ditolak',
                                        'accepted' => 'Diterima',
                                        'enrolled' => 'Terdaftar (Siswa)',
                                    ])
                                    ->required(),
                                Textarea::make('catatan')->label('Catatan Admin')->rows(3),
                            ]),

                        Section::make('Dokumen Pendukung')
                            ->schema([
                                FileUpload::make('dokumen.kk')->label('Kartu Keluarga')->disk('public')->image()->openable()->downloadable()->columnSpanFull(),
                                FileUpload::make('dokumen.akta')->label('Akta Kelahiran')->disk('public')->image()->openable()->downloadable()->columnSpanFull(),
                                FileUpload::make('dokumen.ijazah')->label('Ijazah')->disk('public')->image()->openable()->downloadable()->columnSpanFull(),
                                FileUpload::make('dokumen.foto')->label('Pas Foto')->disk('public')->image()->openable()->downloadable()->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
