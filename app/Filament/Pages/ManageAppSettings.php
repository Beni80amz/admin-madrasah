<?php

namespace App\Filament\Pages;

use App\Models\AppSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
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
        $this->form->fill([
            'theme_mode' => AppSetting::getThemeMode(),
            'ppdb_active' => AppSetting::isPpdbActive(),
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

        AppSetting::setThemeMode($data['theme_mode']);
        AppSetting::setPpdbActive($data['ppdb_active']);

        Notification::make()
            ->title('Pengaturan berhasil disimpan!')
            ->body('Halaman akan di-refresh untuk menerapkan perubahan...')
            ->success()
            ->send();

        // Force full page reload to apply changes
        $this->js('setTimeout(() => { window.location.reload(); }, 500);');
    }
}
