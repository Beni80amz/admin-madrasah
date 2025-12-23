<?php

namespace App\Filament\Pages;

use App\Models\AppSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use BackedEnum;
use UnitEnum;

class ManageAppSettings extends Page implements HasForms
{
    use InteractsWithForms;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['Superadmin', 'Admin PPDB']);
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan Aplikasi';

    protected static ?string $slug = 'settings';

    protected static UnitEnum|string|null $navigationGroup = 'Setting';

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public function getView(): string
    {
        return 'filament.pages.manage-app-settings';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Pengaturan Aplikasi';
    }

    public function mount(): void
    {
        $ppdbInfo = AppSetting::getPpdbInfo();

        $this->form->fill([
            'theme_mode' => AppSetting::getThemeMode(),
            'ppdb_active' => AppSetting::isPpdbActive(),
            'ppdb_tahun_ajaran' => $ppdbInfo['tahun_ajaran'],
            'ppdb_tanggal_mulai' => $ppdbInfo['period']['start'],
            'ppdb_tanggal_selesai' => $ppdbInfo['period']['end'],
            'ppdb_kuota' => $ppdbInfo['kuota'],
            'ppdb_biaya' => $ppdbInfo['biaya'],
            'ppdb_alur' => $ppdbInfo['alur'],
            'ppdb_persyaratan' => collect($ppdbInfo['persyaratan'])->map(fn($item) => ['item' => $item])->toArray(),
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Pengaturan Tema')
                    ->description('Pilih mode tampilan untuk website (Frontend & Backend)')
                    ->icon('heroicon-o-paint-brush')
                    ->schema([
                        Radio::make('theme_mode')
                            ->label('Mode Tema')
                            ->options([
                                'dark' => '🌙 Dark Mode',
                                'light' => '☀️ Light Mode',
                                'custom' => '🎨 Custom Mode',
                            ])
                            ->descriptions([
                                'dark' => 'Website akan selalu tampil dalam mode gelap',
                                'light' => 'Website akan selalu tampil dalam mode terang',
                                'custom' => 'Tombol toggle tema akan muncul, pengunjung dapat memilih mode sesuai preferensi mereka',
                            ])
                            ->required()
                            ->default('dark')
                            ->inline(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Fitur PPDB')
                    ->description('Pengaturan Penerimaan Peserta Didik Baru')
                    ->icon('heroicon-o-user-plus')
                    ->schema([
                        \Filament\Forms\Components\Toggle::make('ppdb_active')
                            ->label('Aktifkan Fitur PPDB')
                            ->helperText('Jika diaktifkan, menu Pendaftaran Siswa Baru akan muncul di halaman depan website')
                            ->default(false),
                    ]),

                Section::make('Informasi PPDB')
                    ->description('Konfigurasi informasi yang ditampilkan di halaman PPDB')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('ppdb_tahun_ajaran')
                            ->label('Tahun Ajaran')
                            ->placeholder('Contoh: 2024/2025')
                            ->required()
                            ->maxLength(20),

                        Grid::make(2)
                            ->schema([
                                DatePicker::make('ppdb_tanggal_mulai')
                                    ->label('Tanggal Mulai Pendaftaran')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d F Y'),

                                DatePicker::make('ppdb_tanggal_selesai')
                                    ->label('Tanggal Batas Pendaftaran')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d F Y'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('ppdb_kuota')
                                    ->label('Kuota Penerimaan')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->suffix('siswa'),

                                TextInput::make('ppdb_biaya')
                                    ->label('Biaya Pendaftaran')
                                    ->placeholder('Contoh: Gratis atau Rp 100.000')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Alur Pendaftaran')
                    ->description('Langkah-langkah alur pendaftaran PPDB')
                    ->icon('heroicon-o-queue-list')
                    ->schema([
                        Repeater::make('ppdb_alur')
                            ->label('')
                            ->schema([
                                TextInput::make('step')
                                    ->label('Nomor Langkah')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->columnSpan(1),

                                TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(100)
                                    ->columnSpan(2),

                                Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->defaultItems(5)
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => isset($state['step'], $state['title']) ? "Langkah {$state['step']}: {$state['title']}" : null)
                            ->addActionLabel('Tambah Langkah'),
                    ]),

                Section::make('Persyaratan Pendaftaran')
                    ->description('Daftar dokumen persyaratan pendaftaran')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Repeater::make('ppdb_persyaratan')
                            ->label('')
                            ->schema([
                                TextInput::make('item')
                                    ->label('Persyaratan')
                                    ->required()
                                    ->maxLength(200)
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(5)
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['item'] ?? null)
                            ->addActionLabel('Tambah Persyaratan'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save theme settings
        AppSetting::setThemeMode($data['theme_mode']);

        // Save PPDB settings
        AppSetting::setPpdbActive($data['ppdb_active']);
        AppSetting::setPpdbTahunAjaran($data['ppdb_tahun_ajaran']);
        AppSetting::setPpdbTanggalMulai($data['ppdb_tanggal_mulai']);
        AppSetting::setPpdbTanggalSelesai($data['ppdb_tanggal_selesai']);
        AppSetting::setPpdbKuota((int) $data['ppdb_kuota']);
        AppSetting::setPpdbBiaya($data['ppdb_biaya']);
        AppSetting::setPpdbAlur($data['ppdb_alur']);

        // Transform persyaratan from repeater format to simple array
        $persyaratan = collect($data['ppdb_persyaratan'])->pluck('item')->toArray();
        AppSetting::setPpdbPersyaratan($persyaratan);

        Notification::make()
            ->title('Pengaturan berhasil disimpan!')
            ->body('Halaman akan di-refresh untuk menerapkan perubahan...')
            ->success()
            ->send();

        // Force full page reload to apply changes
        $this->js('setTimeout(() => { window.location.reload(); }, 500);');
    }
}
