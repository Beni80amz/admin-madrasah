<?php

namespace App\Filament\Pages;

use App\Models\Teacher;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class MyProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.my-profile';

    public ?array $data = [];

    public $teacher;

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-user-circle';
    }

    public static function getNavigationLabel(): string
    {
        return 'Profil Saya';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Setting';
    }

    public static function getNavigationSort(): ?int
    {
        return -1;
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return 'my-profile';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Profil Saya';
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
            ->model($this->teacher ?? Teacher::class)
            ->statePath('data')
            ->components([
                Grid::make(3)
                    ->schema([
                        // Left Column: Profile Photo & Status (1 col)
                        Section::make()
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('photo')
                                    ->label('Foto Profil')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->disk('public')
                                    ->directory('teachers')
                                    ->columnSpanFull()
                                    ->alignCenter(),

                                Toggle::make('is_active')
                                    ->label('Status Akun Aktif')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false)
                                    ->disabled()
                                    ->default(true)
                                    ->columnSpanFull(),
                            ]),

                        // Right Column: Personal & Employment Data (2 cols)
                        Section::make()
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Informasi Pribadi')
                                    ->description('Informasi dasar identitas Anda.')
                                    ->icon('heroicon-o-user')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('nama_lengkap')
                                                    ->label('Nama Lengkap')
                                                    ->required(),
                                                TextInput::make('nip')
                                                    ->label('NIP/NIK')
                                                    ->required(),
                                                TextInput::make('nuptk')
                                                    ->label('NUPTK'),
                                                TextInput::make('npk_peg_id')
                                                    ->label('NPK/Peg.ID'),
                                            ]),
                                    ])
                                    ->collapsible(),

                                Section::make('Detail Kepegawaian')
                                    ->description('Informasi terkait jabatan dan status di madrasah.')
                                    ->icon('heroicon-o-briefcase')
                                    ->schema([
                                        Grid::make(2)
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

                                                Select::make('tugas_tambahan_id')
                                                    ->label('Tugas Tambahan')
                                                    ->relationship('tugasTambahan', 'nama')
                                                    ->searchable()
                                                    ->preload(),

                                                Select::make('mata_pelajaran_id')
                                                    ->label('Mata Pelajaran (Jika Guru Mapel)')
                                                    ->relationship('mataPelajaran', 'nama')
                                                    ->searchable()
                                                    ->preload()
                                                    ->visible(
                                                        fn($get) =>
                                                        \App\Models\TugasPokok::find($get('tugas_pokok_id'))?->nama === 'Guru Mata Pelajaran'
                                                    ),

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
                                            ]),
                                    ])
                                    ->collapsible(),

                                // Form Actions Footer
                                \Filament\Schemas\Components\Actions::make([
                                    Action::make('save')
                                        ->label('Simpan Perubahan')
                                        ->icon('heroicon-m-check-circle')
                                        ->submit('save')
                                        ->keyBindings(['mod+s']),
                                ])
                                    ->fullWidth()
                                    ->alignEnd(),
                            ]),
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
