<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use UnitEnum;
use BackedEnum;

class FormUnduhan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationLabel = 'Form Unduhan';

    protected static ?string $title = 'Form Unduhan';

    protected static ?string $slug = 'form-unduhan';

    protected static UnitEnum|string|null $navigationGroup = 'Setting';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.form-unduhan';

    public static function canAccess(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        return $user->hasRole('Superadmin') || $user->hasRole('super_admin');
    }
}
