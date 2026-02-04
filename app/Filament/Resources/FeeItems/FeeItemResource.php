<?php

namespace App\Filament\Resources\FeeItems;

use App\Filament\Resources\FeeItems\Pages\CreateFeeItem;
use App\Filament\Resources\FeeItems\Pages\EditFeeItem;
use App\Filament\Resources\FeeItems\Pages\ListFeeItems;
use App\Models\FeeCategory;
use App\Models\FeeItem;
use App\Models\TahunAjaran;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class FeeItemResource extends Resource
{
    protected static ?string $model = FeeItem::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static UnitEnum|string|null $navigationGroup = 'Madrasah Pay';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Item Biaya';

    protected static ?string $modelLabel = 'Item Biaya';

    protected static ?string $pluralModelLabel = 'Item Biaya';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('fee_category_id')
                    ->label('Kategori Tagihan')
                    ->options(FeeCategory::active()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->options(TahunAjaran::orderBy('nama', 'desc')->pluck('nama', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('name')
                    ->label('Nama Item')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: SPP Bulan Januari'),
                TextInput::make('amount')
                    ->label('Nominal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->placeholder('100000'),
                Select::make('frequency')
                    ->label('Frekuensi')
                    ->options([
                        'monthly' => 'Bulanan',
                        'installment' => 'Cicilan',
                        'once' => 'Sekali Bayar',
                    ])
                    ->default('once')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('feeCategory.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Item')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tahunAjaran.nama')
                    ->label('Tahun Ajaran')
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('frequency')
                    ->label('Frekuensi')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'monthly' => 'Bulanan',
                        'installment' => 'Cicilan',
                        default => 'Sekali Bayar',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'monthly' => 'info',
                        'installment' => 'warning',
                        default => 'success',
                    }),
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('fee_category_id')
                    ->label('Kategori')
                    ->options(FeeCategory::pluck('name', 'id')),
                SelectFilter::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->options(TahunAjaran::orderBy('nama', 'desc')->pluck('nama', 'id')),
                SelectFilter::make('frequency')
                    ->label('Frekuensi')
                    ->options([
                        'monthly' => 'Bulanan',
                        'installment' => 'Cicilan',
                        'once' => 'Sekali Bayar',
                    ]),
            ])
            ->recordActions([
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
            'index' => ListFeeItems::route('/'),
            'create' => CreateFeeItem::route('/create'),
            'edit' => EditFeeItem::route('/{record}/edit'),
        ];
    }
}
