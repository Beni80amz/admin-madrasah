<?php

namespace App\Filament\Resources\StudentUpdateActions;

use App\Filament\Resources\StudentUpdateActions\Pages\ManageStudentUpdateActions;
use App\Models\StudentUpdateAction;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class StudentUpdateActionResource extends Resource
{
    protected static ?string $model = StudentUpdateAction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Daftar Tindakan';

    protected static ?string $pluralLabel = 'Daftar Tindakan';

    protected static ?string $modelLabel = 'Daftar Tindakan';

    protected static UnitEnum|string|null $navigationGroup = 'Administrasi Siswa';

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum', 'kepala_sekolah', 'Kepala Sekolah']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('requester.name')
                    ->label('Pengaju')
                    ->sortable(),
                TextColumn::make('student.nama_lengkap')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('changes')
                    ->label('Perubahan')
                    ->formatStateUsing(function ($state) {
                        $output = [];
                        foreach ($state as $field => $values) {
                            $fieldName = ucwords(str_replace('_', ' ', $field));
                            $output[] = "<strong>{$fieldName}</strong>: <span style='color: #ef4444;'>{$values['old']}</span> → <span style='color: #10b981;'>{$values['new']}</span>";
                        }
                        return implode('<br>', $output);
                    })
                    ->html(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('approve')
                    ->label('Setuju')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->visible(fn(StudentUpdateAction $record) => $record->status === 'pending')
                    ->action(function (StudentUpdateAction $record) {
                        $student = $record->student;
                        $changes = $record->changes;

                        $updateData = [];
                        foreach ($changes as $field => $values) {
                            $updateData[$field] = $values['new'];
                        }

                        $student->update($updateData);

                        $record->update([
                            'status' => 'approved',
                            'verifier_id' => auth()->id(),
                            'verified_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Perubahan Disetujui')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->requiresConfirmation()
                    ->form([
                        \Filament\Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->visible(fn(StudentUpdateAction $record) => $record->status === 'pending')
                    ->action(function (StudentUpdateAction $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                            'verifier_id' => auth()->id(),
                            'verified_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Perubahan Ditolak')
                            ->danger()
                            ->send();
                    }),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStudentUpdateActions::route('/'),
        ];
    }
}
