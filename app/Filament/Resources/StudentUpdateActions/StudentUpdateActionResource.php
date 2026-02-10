<?php

namespace App\Filament\Resources\StudentUpdateActions;

use App\Filament\Resources\StudentUpdateActions\Pages\ManageStudentUpdateActions;
use App\Filament\Resources\StudentUpdateActions\Pages\ViewStudentUpdateAction;
use App\Models\StudentUpdateAction;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
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

    protected static UnitEnum|string|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum', 'kepala_sekolah', 'Kepala Sekolah']);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Only show pending actions in the list
        return parent::getEloquentQuery()->where('status', 'pending');
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
                    ->sortable()
                    ->url(fn(StudentUpdateAction $record) => static::getUrl('view', ['record' => $record]))
                    ->weight('bold')
                    ->color('info'),
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
                ViewAction::make()
                    ->label('Detail Perubahan')
                    ->button()
                    ->color('info')
                    ->icon('heroicon-o-eye'),
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
            'view' => ViewStudentUpdateAction::route('/{record}'),
        ];
    }
}
