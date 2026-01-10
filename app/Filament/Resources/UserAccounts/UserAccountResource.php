<?php

namespace App\Filament\Resources\UserAccounts;

use App\Filament\Resources\UserAccounts\Pages\ListUserAccounts;
use App\Filament\Resources\UserAccounts\Tables\UserAccountsTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use UnitEnum;

class UserAccountResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static UnitEnum|string|null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Akun Pengguna';

    protected static ?string $slug = 'user-accounts';

    public static function table(Table $table): Table
    {
        return UserAccountsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserAccounts::route('/'),
        ];
    }
}
