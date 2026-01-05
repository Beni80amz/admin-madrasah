<?php

namespace App\Filament\Resources\LinkPendataans;

use App\Filament\Resources\LinkPendataans\Pages\CreateLinkPendataan;
use App\Filament\Resources\LinkPendataans\Pages\EditLinkPendataan;
use App\Filament\Resources\LinkPendataans\Pages\ListLinkPendataans;
use App\Models\LinkPendataan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use UnitEnum;
use BackedEnum;

class LinkPendataanResource extends Resource
{
    protected static ?string $model = LinkPendataan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-link';

    protected static UnitEnum|string|null $navigationGroup = 'Setting';

    protected static ?string $navigationLabel = 'Link Pendataan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Link')
                    ->required()
                    ->maxLength(255),
                TextInput::make('url')
                    ->label('URL Tujuan')
                    ->url()
                    ->required()
                    ->maxLength(255),
                FileUpload::make('image')
                    ->label('Gambar / Icon')
                    ->image()
                    ->disk('public')
                    ->directory('link-pendataan')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Icon'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinkPendataans::route('/'),
            'create' => CreateLinkPendataan::route('/create'),
            'edit' => EditLinkPendataan::route('/{record}/edit'),
        ];
    }
}
