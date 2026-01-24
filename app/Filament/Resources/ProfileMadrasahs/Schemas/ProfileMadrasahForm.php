<?php

namespace App\Filament\Resources\ProfileMadrasahs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfileMadrasahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Madrasah')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo Madrasah')
                            ->image()
                            ->disk('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->maxSize(1024)
                            ->directory('profile')
                            ->imageEditor()
                            ->columnSpanFull(),
                        TextInput::make('nama_madrasah')
                            ->label('Nama Madrasah')
                            ->required(),
                        \Filament\Forms\Components\Select::make('jenjang_id')
                            ->label('Jenjang Pendidikan')
                            ->options([
                                1 => 'RA (Raudhatul Athfal)',
                                2 => 'MI (Madrasah Ibtidaiyah)',
                                3 => 'MTs (Madrasah Tsanawiyah)',
                                4 => 'MA (Madrasah Aliyah)',
                            ])
                            ->default(2)
                            ->required()
                            ->helperText('Pilih jenjang untuk filter sinkronisasi RDM'),
                        TextInput::make('motto')
                            ->label('Motto Madrasah')
                            ->placeholder('Contoh: Membangun Generasi Islami Berprestasi')
                            ->helperText('Ditampilkan sebagai headline utama di halaman beranda')
                            ->columnSpanFull(),
                        TextInput::make('nsm')
                            ->label('NSM (Nomor Statistik Madrasah)'),
                        TextInput::make('npsn')
                            ->label('NPSN (Nomor Pokok Sekolah Nasional)'),
                        TextInput::make('tahun_berdiri')
                            ->label('Tahun Berdiri'),
                        Textarea::make('alamat')
                            ->label('Alamat Madrasah')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('running_text')
                            ->label('Teks Berjalan (Monitor)')
                            ->placeholder('Contoh: Selamat Datang di MIS Al-Islamiyah AMZ • Jagalah Kebersihan • ...')
                            ->helperText('Teks ini akan muncul berjalan di bagian bawah halaman Monitor Absensi.')
                            ->rows(2)
                            ->columnSpanFull(),
                        Textarea::make('google_maps_embed')
                            ->label('Google Maps Embed Code')
                            ->rows(5)
                            ->placeholder('<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" ...></iframe>')
                            ->helperText('Salin dan tempel kode embed (iframe HTML) dari Google Maps di sini.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Kontak & Media Sosial')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email Resmi')
                            ->email()
                            ->placeholder('Contoh: info@madrasah.sch.id'),
                        TextInput::make('no_hp')
                            ->label('Nomor Telepon')
                            ->placeholder('Contoh: +62 82110863967'),
                        TextInput::make('whatsapp')
                            ->label('Nomor WhatsApp')
                            ->placeholder('Contoh: 6282110863967')
                            ->helperText('Format tanpa + dan spasi'),
                        TextInput::make('facebook')
                            ->label('URL Facebook')
                            ->url()
                            ->placeholder('Contoh: https://facebook.com/madrasah'),
                        TextInput::make('instagram')
                            ->label('URL Instagram')
                            ->url()
                            ->placeholder('Contoh: https://instagram.com/madrasah'),
                        TextInput::make('youtube')
                            ->label('URL YouTube')
                            ->url()
                            ->placeholder('Contoh: https://youtube.com/@madrasah'),
                    ])
                    ->columns(2),

                Section::make('Sejarah & Visi Misi')
                    ->schema([
                        RichEditor::make('sejarah_singkat')
                            ->label('Sejarah Singkat')
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'blockquote',
                                'link',
                                'undo',
                                'redo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('profile-content')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                        RichEditor::make('visi')
                            ->label('Visi')
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'blockquote',
                                'link',
                                'undo',
                                'redo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('profile-content')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                        RichEditor::make('misi')
                            ->label('Misi')
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'blockquote',
                                'link',
                                'undo',
                                'redo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('profile-content')
                            ->fileAttachmentsVisibility('public')
                            ->placeholder('Contoh: Mewujudkan pendidikan berkualitas yang berlandaskan nilai-nilai Islam...')
                            ->helperText('Ditampilkan sebagai deskripsi di section Misi pada halaman Profil')
                            ->columnSpanFull(),
                        RichEditor::make('tujuan_madrasah')
                            ->label('Tujuan Madrasah')
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'blockquote',
                                'link',
                                'undo',
                                'redo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('profile-content')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ]),


                Section::make('Kepala Madrasah')
                    ->schema([
                        TextInput::make('nama_kepala_madrasah')
                            ->label('Nama Kepala Madrasah')
                            ->placeholder('Contoh: Dr. H. Ahmad Fauzi, M.Pd.I'),
                        TextInput::make('nip_kepala_madrasah')
                            ->label('NIP Kepala Madrasah')
                            ->placeholder('Contoh: 197001011990031001'),
                        FileUpload::make('foto_kepala_madrasah')
                            ->label('Foto Kepala Madrasah')
                            ->image()
                            ->disk('public')
                            ->directory('profile')
                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                            ->maxSize(2048)
                            ->imageEditor()
                            ->circleCropper()
                            ->columnSpan(1),
                        FileUpload::make('tanda_tangan_kepala_madrasah')
                            ->label('Tanda Tangan Kepala Madrasah')
                            ->image()
                            ->disk('public')
                            ->directory('profile')
                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                            ->maxSize(512)
                            ->helperText('Upload gambar tanda tangan (PNG transparan disarankan)')
                            ->columnSpan(1),
                        FileUpload::make('stempel_madrasah')
                            ->label('Stempel Madrasah')
                            ->image()
                            ->disk('public')
                            ->directory('profile')
                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                            ->maxSize(512)
                            ->helperText('Upload gambar stempel madrasah (PNG transparan disarankan)')
                            ->columnSpan(1),
                        RichEditor::make('kata_pengantar')
                            ->label('Kata Pengantar')
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'link',
                                'undo',
                                'redo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('profile-content')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
