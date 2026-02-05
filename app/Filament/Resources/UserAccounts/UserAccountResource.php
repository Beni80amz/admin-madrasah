<?php

namespace App\Filament\Resources\UserAccounts;

use App\Filament\Resources\UserAccounts\Pages\CreateUserAccount;
use App\Filament\Resources\UserAccounts\Pages\EditUserAccount;
use App\Filament\Resources\UserAccounts\Pages\ListUserAccounts;
use App\Filament\Resources\UserAccounts\Tables\UserAccountsTable;
use App\Models\User;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use UnitEnum;

class UserAccountResource extends Resource
{
    protected static ?string $model = User::class;

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return !auth()->user()->hasRole('Admin Keuangan');
    // }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static UnitEnum|string|null $navigationGroup = 'Users';

    protected static ?string $navigationLabel = 'Akun Pengguna';

    protected static ?string $slug = 'user-accounts';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Lengkap'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Email'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->label('Password'),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('Role')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active Status')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return UserAccountsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserAccounts::route('/'),
            'create' => CreateUserAccount::route('/create'),
            'edit' => EditUserAccount::route('/{record}/edit'),
        ];
    }
}
