<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class MyProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.my-profile';

    public ?array $data = [];

    public $teacher;

    public static function getNavigationIcon(): \BackedEnum|\Illuminate\Contracts\Support\Htmlable|string|null
    {
        return 'heroicon-o-user-circle';
    }

    public static function getNavigationLabel(): string
    {
        return 'Profil Saya';
    }

    public static function getNavigationGroup(): \UnitEnum|string|null
    {
        return 'Setting';
    }

    public static function getNavigationSort(): ?int
    {
        return -1;
    }

    public function getTitle(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return 'Profil Saya';
    }

    public static function getSlug(): string
    {
        return 'my-profile';
    }

    public function mount(): void
    {
        $user = Auth::user();
        $this->teacher = $user->teacher;

        $state = $this->teacher ? $this->teacher->toArray() : [
            'nama_lengkap' => $user->name,
            'nip' => $user->email,
        ];

        $this->getSchema('form')->fill($state);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model($this->teacher)
            ->statePath('data')
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Informasi Pribadi')
                            ->description('Perbarui foto dan informasi dasar profil Anda.')
                            ->schema([
                                FileUpload::make('photo')
                                    ->label('Foto Profil')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->circle()
                                    ->disk('public')
                                    ->directory('teachers')
                                    ->columnSpanFull()
                                    ->alignCenter(),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('nama_lengkap')
                                            ->label('Nama Lengkap')
                                            ->required()
                                            ->placeholder('Masukkan nama lengkap Anda'),

                                        TextInput::make('nip')
                                            ->label('NIP/NIK')
                                            ->required()
                                            ->placeholder('Masukkan NIP atau NIK'),

                                        TextInput::make('nuptk')
                                            ->label('NUPTK')
                                            ->placeholder('Masukkan NUPTK (jika ada)'),

                                        TextInput::make('npk_peg_id')
                                            ->label('NPK/Peg.ID')
                                            ->placeholder('Masukkan NPK atau Peg.ID'),
                                    ]),
                            ])
                            ->columnSpan(2),

                        Section::make('Detail Kepegawaian')
                            ->description('Informasi terkait jabatan dan status di madrasah.')
                            ->schema([
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
                                    ->required(),

                                Select::make('sertifikasi')
                                    ->label('Sertifikasi')
                                    ->options([
                                        'Sudah' => 'Sudah',
                                        'Belum' => 'Belum',
                                    ])
                                    ->required(),

                                Toggle::make('is_active')
                                    ->label('Status Akun Aktif')
                                    ->disabled()
                                    ->dehydrated(false),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->icon('heroicon-m-check-circle')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->getSchema('form')->getState();
            $user = Auth::user();
            $teacher = $user->teacher;

            if ($teacher) {
                $teacher->update($data);

                if (isset($data['nama_lengkap']) && $user->name !== $data['nama_lengkap']) {
                    $user->update(['name' => $data['nama_lengkap']]);
                }
            } else {
                if (isset($data['nama_lengkap'])) {
                    $user->update(['name' => $data['nama_lengkap']]);
                }
            }

            Notification::make()
                ->title('Profil Berhasil Diperbarui')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Memperbarui Profil')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && ($user->hasRole(['teacher', 'Teacher', 'Guru', 'super_admin', 'Superadmin', 'admin', 'Admin', 'kepala_sekolah', 'Kepala Sekolah']));
    }
}
