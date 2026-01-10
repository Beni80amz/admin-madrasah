<?php

namespace App\Filament\Resources\SyncErrorLogs;

use App\Filament\Resources\SyncErrorLogs\Pages\ManageSyncErrorLogs;
use App\Models\SyncErrorLog;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class SyncErrorLogResource extends Resource
{
    protected static ?string $model = SyncErrorLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static UnitEnum|string|null $navigationGroup = 'Data Pendukung';

    protected static ?int $navigationSort = 99;

    protected static ?string $navigationLabel = 'Log Error Sinkronisasi';

    protected static ?string $modelLabel = 'Error Sync';

    protected static ?string $pluralModelLabel = 'Error Sync';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama')->label('Nama'),
                TextEntry::make('nis_nip')->label('NIS/NIP'),
                TextEntry::make('kelas')->label('Kelas'),
                TextEntry::make('error_type')->label('Tipe Error')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'null_column' => 'danger',
                        'duplicate' => 'warning',
                        'invalid_format' => 'info',
                        default => 'gray',
                    }),
                TextEntry::make('error_column')->label('Kolom Bermasalah'),
                TextEntry::make('error_message')->label('Pesan Error')->columnSpanFull(),
                TextEntry::make('created_at')->label('Waktu')->dateTime('d M Y H:i'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('nama')
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Siswa/Guru')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nis_nip')
                    ->label('NIS/NIP')
                    ->searchable(),
                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->sortable(),
                TextColumn::make('error_type')
                    ->label('Tipe Error')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'null_column' => 'Kolom Kosong',
                        'duplicate' => 'Duplikat',
                        'invalid_format' => 'Format Salah',
                        default => 'Lainnya',
                    })
                    ->color(fn($state) => match ($state) {
                        'null_column' => 'danger',
                        'duplicate' => 'warning',
                        'invalid_format' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('error_column')
                    ->label('Kolom')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'nama_ayah' => 'Nama Ayah',
                        'nama_ibu' => 'Nama Ibu',
                        'nisn' => 'NISN',
                        'nik' => 'NIK',
                        'alamat' => 'Alamat',
                        default => $state,
                    }),
                IconColumn::make('is_resolved')
                    ->label('Selesai')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('error_type')
                    ->label('Tipe Error')
                    ->options([
                        'null_column' => 'Kolom Kosong',
                        'duplicate' => 'Duplikat',
                        'invalid_format' => 'Format Salah',
                        'unknown' => 'Lainnya',
                    ]),
                SelectFilter::make('sync_type')
                    ->label('Jenis Data')
                    ->options([
                        'student' => 'Siswa',
                        'teacher' => 'Guru',
                    ]),
                TernaryFilter::make('is_resolved')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah Diperbaiki')
                    ->falseLabel('Belum Diperbaiki'),
            ])
            ->recordActions([
                ViewAction::make(),
                \Filament\Actions\Action::make('markResolved')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn(SyncErrorLog $record) => $record->update(['is_resolved' => true]))
                    ->requiresConfirmation()
                    ->hidden(fn(SyncErrorLog $record) => $record->is_resolved),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('markAllResolved')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn($records) => $records->each->update(['is_resolved' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSyncErrorLogs::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::unresolved()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
