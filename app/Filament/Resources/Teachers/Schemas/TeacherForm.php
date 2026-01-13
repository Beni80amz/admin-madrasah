<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('photo')
                    ->label('Foto')
                    ->image()
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->maxSize(2048)
                    ->directory('teachers')
                    ->imageEditor()
                    ->circleCropper()
                    ->columnSpanFull(),
                TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('nip')
                    ->label('NIP/NIK')
                    ->rules([
                        'required',
                        'numeric',
                        'digits_between:3,18',
                    ])
                    ->unique(table: 'teachers', column: 'nip', ignoreRecord: true)
                    ->validationMessages([
                        'required' => 'NIP/NIK wajib diisi.',
                        'unique' => 'NIP/NIK sudah digunakan.',
                        'numeric' => 'NIP/NIK harus berupa angka.',
                        'digits_between' => 'NIP/NIK harus berisi antara 3 sampai 18 digit angka.',
                    ]),
                TextInput::make('nuptk')
                    ->label('NUPTK')
                    ->regex('/^[0-9]+$/')
                    ->maxLength(20),
                TextInput::make('npk_peg_id')
                    ->label('NPK/Peg.ID')
                    ->maxLength(30),
                Select::make('jabatan_id')
                    ->label('Jabatan')
                    ->relationship('jabatan', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('tugas_pokok_id')
                    ->label('Tugas Pokok')
                    ->relationship('tugasPokok', 'nama')
                    ->searchable()
                    ->preload()
                    ->placeholder('-')
                    ->live(),
                Select::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->relationship('mataPelajaran', 'nama')
                    ->searchable()
                    ->preload()
                    ->visible(
                        fn($get) =>
                        \App\Models\TugasPokok::find($get('tugas_pokok_id'))?->nama === 'Guru Mata Pelajaran'
                    ),
                Select::make('tugas_tambahan_id')
                    ->label('Tugas Tambahan')
                    ->relationship('tugasTambahan', 'nama')
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->label('Status Kepegawaian')
                    ->options([
                        'PNS' => 'PNS',
                        'Non PNS' => 'Non PNS',
                        'P3K' => 'P3K',
                    ])
                    ->default('Non PNS')
                    ->required(),
                Select::make('sertifikasi')
                    ->label('Sertifikasi')
                    ->options([
                        'Sudah' => 'Sudah',
                        'Belum' => 'Belum',
                    ])
                    ->default('Belum')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required()
                    ->default(true),
            ]);
    }
}
