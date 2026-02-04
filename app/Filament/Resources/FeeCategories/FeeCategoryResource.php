<?php

namespace App\Filament\Resources\FeeCategories;

use App\Filament\Resources\FeeCategories\Pages\CreateFeeCategory;
use App\Filament\Resources\FeeCategories\Pages\EditFeeCategory;
use App\Filament\Resources\FeeCategories\Pages\ListFeeCategories;
use App\Models\FeeCategory;
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

class FeeCategoryResource extends Resource
{
    protected static ?string $model = FeeCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static UnitEnum|string|null $navigationGroup = 'Madrasah Pay';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Kategori Tagihan';

    protected static ?string $modelLabel = 'Kategori Tagihan';

    protected static ?string $pluralModelLabel = 'Kategori Tagihan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: SPP, Seragam, Buku'),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->placeholder('Deskripsi kategori tagihan'),
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
            'index' => ListFeeCategories::route('/'),
            'create' => CreateFeeCategory::route('/create'),
            'edit' => EditFeeCategory::route('/{record}/edit'),
        ];
    }
}
