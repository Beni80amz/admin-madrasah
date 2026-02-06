<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Expenses\Pages\CreateExpense;
use App\Filament\Resources\Expenses\Pages\EditExpense;
use App\Filament\Resources\Expenses\Pages\ListExpenses;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static UnitEnum|string|null $navigationGroup = 'Pelacakan Keuangan';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Data Pengeluaran';

    protected static ?string $modelLabel = 'Pengeluaran';

    protected static ?string $pluralModelLabel = 'Data Pengeluaran';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('Admin Keuangan') || auth()->user()->hasRole('Superadmin');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('expense_category_id')
                    ->label('Kategori Pengeluaran')
                    ->options(ExpenseCategory::active()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                DatePicker::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->required()
                    ->default(now()),
                TextInput::make('amount')
                    ->label('Nominal (Rp)')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(1)
                    ->helperText('Pengeluaran > Rp 5.000.000 memerlukan approval Superadmin'),
                TextInput::make('recipient')
                    ->label('Penerima')
                    ->maxLength(255)
                    ->placeholder('Contoh: PT ABC, Bpk Budi'),
                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options(Expense::getPaymentMethodOptions()),
                TextInput::make('reference_number')
                    ->label('Nomor Referensi/Invoice')
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->rows(2),
                FileUpload::make('attachment')
                    ->label('Bukti Transaksi (Opsional)')
                    ->image()
                    ->directory('expenses')
                    ->maxSize(2048)
                    ->helperText('Upload foto kwitansi/invoice (max 2MB)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_number')
                    ->label('No. Transaksi')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('expenseCategory.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('recipient')
                    ->label('Penerima')
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => Expense::getStatusOptions()[$state] ?? $state),
                TextColumn::make('user.name')
                    ->label('Dicatat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('approver.name')
                    ->label('Disetujui Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->filters([
                SelectFilter::make('expense_category_id')
                    ->label('Kategori')
                    ->options(ExpenseCategory::pluck('name', 'id')),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Expense::getStatusOptions()),
                Filter::make('requires_approval')
                    ->label('Butuh Approval')
                    ->query(fn(Builder $query): Builder => $query->where('requires_approval', true)->where('status', 'pending')),
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn(Expense $record): bool => $record->status !== 'rejected'),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(
                        fn(Expense $record): bool =>
                        $record->isPending() &&
                        auth()->user()->hasRole('Superadmin')
                    )
                    ->action(fn(Expense $record) => $record->approve(auth()->id())),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(
                        fn(Expense $record): bool =>
                        $record->isPending() &&
                        auth()->user()->hasRole('Superadmin')
                    )
                    ->action(fn(Expense $record) => $record->reject(auth()->id())),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpenses::route('/'),
            'create' => CreateExpense::route('/create'),
            'edit' => EditExpense::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = static::getModel()::where('status', 'pending')->count();
        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
