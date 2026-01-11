<?php

namespace App\Filament\Resources\UserAccounts\Tables;

use App\Models\User;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Collection;
use Filament\Actions\Action;

class UserAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->formatStateUsing(fn(User $record) => $record->teacher?->nama_lengkap ?? $record->student?->nama_lengkap ?? $record->name)
                    ->description(fn(User $record): string => match (true) {
                        !is_null($record->teacher) => $record->teacher->nip ?? $record->teacher->nuptk ?? '-',
                        !is_null($record->student) => $record->student->nis_lokal ?? $record->student->nisn ?? $record->student->nik ?? '-',
                        default => $record->email // Fallback to email/username if not linked
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Username / Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'teacher' => 'info',
                        'student' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Filter Role'),
            ])
            ->actions([
                Action::make('editRole')
                    ->label('Ubah Role')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->modalHeading('Ubah Role Pengguna')
                    ->modalDescription(fn(User $record) => 'Mengubah role untuk: ' . ($record->teacher?->nama_lengkap ?? $record->student?->nama_lengkap ?? $record->name))
                    ->form([
                        \Filament\Forms\Components\Select::make('roles')
                            ->label('Role')
                            ->multiple()
                            ->options(\Spatie\Permission\Models\Role::pluck('name', 'name'))
                            ->default(fn(User $record) => $record->roles->pluck('name')->toArray())
                            ->required(),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->syncRoles($data['roles']);

                        \Filament\Notifications\Notification::make()
                            ->title('Role berhasil diubah')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('export_csv')
                        ->label('Export ke CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                echo "Nama Lengkap,Username/Email,Role,Nama Asli (Jika Guru/Siswa)\n";
                                foreach ($records as $record) {
                                    $role = $record->roles->first()?->name ?? '-';
                                    $realName = $record->teacher?->nama_lengkap ?? ($record->student?->nama_lengkap ?? $record->name);

                                    echo "\"{$record->name}\",\"{$record->email}\",\"{$role}\",\"{$realName}\"\n";
                                }
                            }, 'users_export_' . date('Y-m-d_H-i-s') . '.csv');
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
