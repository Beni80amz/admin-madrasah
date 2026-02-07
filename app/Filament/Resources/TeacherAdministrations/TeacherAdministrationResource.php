<?php

namespace App\Filament\Resources\TeacherAdministrations;

use App\Enums\AdministrationCategory;
use App\Enums\AdministrationSubcategory;
use App\Filament\Resources\TeacherAdministrations\Pages\CreateTeacherAdministration;
use App\Filament\Resources\TeacherAdministrations\Pages\ListTeacherAdministrations;
use App\Models\TeacherAdministration;
use App\Services\GoogleDriveService;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class TeacherAdministrationResource extends Resource
{
    protected static ?string $model = TeacherAdministration::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Guru';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Berkas Administrasi';

    protected static ?string $modelLabel = 'Berkas Administrasi';

    protected static ?string $pluralModelLabel = 'Berkas Administrasi';

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && ($user->hasRole(['teacher', 'Teacher', 'Guru', 'super_admin', 'Superadmin', 'admin', 'Admin', 'kepala_sekolah', 'Kepala Sekolah']));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Berkas')
                    ->schema([
                        Select::make('category')
                            ->label('Kategori')
                            ->options(AdministrationCategory::options())
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn($set) => $set('subcategory', null)),

                        Select::make('subcategory')
                            ->label('Sub-Kategori')
                            ->options(function (callable $get) {
                                $category = $get('category');
                                if (!$category) {
                                    return [];
                                }
                                $categoryEnum = AdministrationCategory::tryFrom($category);
                                if (!$categoryEnum) {
                                    return [];
                                }
                                return AdministrationSubcategory::forCategory($categoryEnum);
                            })
                            ->required()
                            ->searchable(),

                        TextInput::make('academic_year')
                            ->label('Tahun Ajaran')
                            ->placeholder('cth: 2025/2026')
                            ->maxLength(20),

                        FileUpload::make('temp_file')
                            ->label('Pilih File')
                            ->required()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->maxSize(51200) // 50MB
                            ->helperText('Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG. Maksimal 50MB.')
                            ->disk('public')
                            ->directory('temp-uploads')
                            ->visibility('public')
                            ->storeFileNamesIn('file_name')
                            ->live(),

                        Hidden::make('file_name'),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2)
                            ->placeholder('Catatan tambahan (opsional)'),

                        Hidden::make('user_id')
                            ->default(fn() => Auth::id()),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $isAdmin = $user && $user->hasAnyRole(['super_admin', 'Superadmin', 'admin', 'Admin', 'kepala_sekolah', 'Kepala Sekolah', 'Admin Keuangan', 'Kurikulum', 'Kesiswaan']);

        return $table
            ->query(function () use ($user, $isAdmin) {
                $query = TeacherAdministration::query();

                // If not admin, only show own files
                if (!$isAdmin) {
                    $query->where('user_id', $user->id);
                }

                return $query->with(['user', 'verifier']);
            })
            ->columns([
                TextColumn::make('user.name')
                    ->label('Guru')
                    ->searchable()
                    ->sortable()
                    ->visible($isAdmin),

                TextColumn::make('category_label')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        str_contains(strtolower($state), 'perencanaan') => 'info',
                        str_contains(strtolower($state), 'pelaksanaan') => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('subcategory_label')
                    ->label('Sub-Kategori')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('file_name')
                    ->label('Nama File')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->file_name),

                TextColumn::make('formatted_file_size')
                    ->label('Ukuran'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'warning',
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => TeacherAdministration::statusOptions()[$state] ?? $state),

                TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(AdministrationCategory::options()),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(TeacherAdministration::statusOptions()),
            ])
            ->actions([
                Action::make('preview')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn($record) => $record->web_view_link)
                    ->openUrlInNewTab(),

                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn($record) => $record->file_url)
                    ->openUrlInNewTab()
                    ->visible(fn() => Auth::user()->hasAnyRole(['super_admin', 'Superadmin', 'kepala_sekolah', 'Kepala Sekolah'])),

                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $isAdmin && $record->status === 'submitted')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->markAsVerified(Auth::id());
                        Notification::make()->title('Berkas berhasil diverifikasi')->success()->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $isAdmin && $record->status === 'submitted')
                    ->form([
                        Textarea::make('rejection_notes')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->markAsRejected(Auth::id(), $data['rejection_notes']);
                        Notification::make()->title('Berkas ditolak')->warning()->send();
                    }),

                DeleteAction::make()
                    ->before(function ($record) {
                        // Delete from Google Drive
                        try {
                            $user = Auth::user();
                            if ($user->googleToken) {
                                $service = app(GoogleDriveService::class);
                                $service->initializeClient($user);
                                $service->deleteFile($record->google_drive_file_id);
                            }
                        } catch (\Exception $e) {
                            // Log but don't fail
                            \Log::warning('Failed to delete file from Drive: ' . $e->getMessage());
                        }
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulkDownload')
                        ->label('Download Terpilih')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn() => Auth::user() && Auth::user()->hasAnyRole(['super_admin', 'Superadmin', 'kepala_sekolah', 'Kepala Sekolah']))
                        ->action(function (\Illuminate\Support\Collection $records, $livewire) {
                            $urlsJson = json_encode($records->pluck('file_url')->toArray());

                            $livewire->js("
                                const data = '{$urlsJson}';
                                JSON.parse(data).forEach((url, index) => {
                                    setTimeout(() => {
                                        window.open(url, '_blank');
                                    }, index * 500);
                                });
                            ");
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeacherAdministrations::route('/'),
            'create' => CreateTeacherAdministration::route('/create'),
        ];
    }
}
