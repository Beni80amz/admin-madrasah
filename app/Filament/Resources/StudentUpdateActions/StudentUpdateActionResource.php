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

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Pengajuan')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(3)
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('created_at')
                                    ->label('Tanggal Pengajuan')
                                    ->dateTime('d F Y H:i'),
                                \Filament\Infolists\Components\TextEntry::make('requester.name')
                                    ->label('Wali Kelas / Guru'),
                                \Filament\Infolists\Components\TextEntry::make('student.nama_lengkap')
                                    ->label('Nama Siswa'),
                            ]),
                    ]),
                \Filament\Schemas\Components\Section::make('Detail Perubahan Data')
                    ->description('Daftar field yang diubah oleh guru dan menunggu persetujuan.')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('id')
                            ->label('')
                            ->columnSpanFull()
                            ->html()
                            ->formatStateUsing(function ($state, StudentUpdateAction $record) {
                                $changes = $record->changes;
                                if (empty($changes))
                                    return '<div class="p-4 text-gray-500 italic">Tidak ada data perubahan yang tercatat.</div>';

                                $output = '<div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">';
                                $output .= '<table class="w-full text-sm text-left border-collapse">';
                                $output .= '<thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase text-gray-700 dark:text-gray-300">';
                                $output .= '<tr>';
                                $output .= '<th class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 font-bold">Field / Data</th>';
                                $output .= '<th class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 font-bold text-red-600 dark:text-red-400">Data Terakhir (Lama)</th>';
                                $output .= '<th class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 font-bold text-green-600 dark:text-green-400">Usulan Baru</th>';
                                $output .= '</tr></thead>';
                                $output .= '<tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">';

                                foreach ($changes as $field => $values) {
                                    $fieldName = ucwords(str_replace('_', ' ', $field));
                                    $old = $values['old'] ?? '-';
                                    $new = $values['new'] ?? '-';

                                    if ($old === null || $old === '')
                                        $old = '-';
                                    if ($new === null || $new === '')
                                        $new = '-';

                                    $output .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">';
                                    $output .= "<td class='px-4 py-3 font-semibold bg-gray-50/30 dark:bg-gray-800/30 w-1/4'>{$fieldName}</td>";
                                    $output .= "<td class='px-4 py-3 text-red-600 dark:text-red-400 font-mono text-xs w-3/8'>{$old}</td>";
                                    $output .= "<td class='px-4 py-3 text-green-600 dark:text-green-400 font-mono text-xs w-3/8'>{$new}</td>";
                                    $output .= '</tr>';
                                }
                                $output .= '</tbody></table></div>';
                                return $output;
                            }),
                    ]),
            ]);
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
