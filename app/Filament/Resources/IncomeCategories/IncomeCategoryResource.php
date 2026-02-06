<?php

namespace App\Filament\Resources\IncomeCategories;

use App\Filament\Resources\IncomeCategories\Pages\CreateIncomeCategory;
use App\Filament\Resources\IncomeCategories\Pages\EditIncomeCategory;
use App\Filament\Resources\IncomeCategories\Pages\ListIncomeCategories;
use App\Models\IncomeCategory;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class IncomeCategoryResource extends Resource
{
    protected static ?string $model = IncomeCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static UnitEnum|string|null $navigationGroup = 'Pelacakan Keuangan';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Kategori Pemasukan';

    protected static ?string $modelLabel = 'Kategori Pemasukan';

    protected static ?string $pluralModelLabel = 'Kategori Pemasukan';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('Admin Keuangan') || auth()->user()->hasRole('Superadmin');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Donasi, BOS, Sumbangan'),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->placeholder('Deskripsi kategori pemasukan'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                TextColumn::make('incomes_count')
                    ->label('Jumlah Transaksi')
                    ->counts('incomes')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => ListIncomeCategories::route('/'),
            'create' => CreateIncomeCategory::route('/create'),
            'edit' => EditIncomeCategory::route('/{record}/edit'),
        ];
    }
}
