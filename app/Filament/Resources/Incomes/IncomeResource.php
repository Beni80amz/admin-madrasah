<?php

namespace App\Filament\Resources\Incomes;

use App\Filament\Resources\Incomes\Pages\CreateIncome;
use App\Filament\Resources\Incomes\Pages\EditIncome;
use App\Filament\Resources\Incomes\Pages\ListIncomes;
use App\Models\Income;
use App\Models\IncomeCategory;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static UnitEnum|string|null $navigationGroup = 'Pelacakan Keuangan';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Data Pemasukan';

    protected static ?string $modelLabel = 'Pemasukan';

    protected static ?string $pluralModelLabel = 'Data Pemasukan';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('Admin Keuangan') || auth()->user()->hasRole('Superadmin');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('income_category_id')
                    ->label('Kategori Pemasukan')
                    ->options(IncomeCategory::active()->pluck('name', 'id'))
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
                    ->minValue(1),
                TextInput::make('period')
                    ->label('Periode')
                    ->placeholder('Contoh: Januari 2026')
                    ->helperText('Untuk akumulasi Madrasah Pay'),
                TextInput::make('source')
                    ->label('Sumber Dana')
                    ->maxLength(255)
                    ->placeholder('Contoh: PT XYZ, Bpk Ahmad'),
                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options(Income::getPaymentMethodOptions()),
                TextInput::make('receipt_number')
                    ->label('Nomor Kwitansi')
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->rows(2),
                FileUpload::make('attachment')
                    ->label('Bukti Transaksi (Opsional)')
                    ->image()
                    ->directory('incomes')
                    ->maxSize(2048)
                    ->helperText('Upload foto kwitansi/bukti (max 2MB)'),
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
                TextColumn::make('incomeCategory.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Sumber')
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('period')
                    ->label('Periode')
                    ->toggleable(),
                IconColumn::make('is_synced')
                    ->label('Sumber')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('info')
                    ->falseColor('success')
                    ->tooltip(fn($record) => $record->is_synced ? 'Data dari Madrasah Pay' : 'Input Manual')
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label('Dicatat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->filters([
                SelectFilter::make('income_category_id')
                    ->label('Kategori')
                    ->options(IncomeCategory::pluck('name', 'id')),
                SelectFilter::make('is_synced')
                    ->label('Jenis Data')
                    ->options([
                        '1' => 'Sync Madrasah Pay',
                        '0' => 'Input Manual',
                    ]),
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
                EditAction::make(),
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
            'index' => ListIncomes::route('/'),
            'create' => CreateIncome::route('/create'),
            'edit' => EditIncome::route('/{record}/edit'),
        ];
    }
}
