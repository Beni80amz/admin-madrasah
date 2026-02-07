<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class TeacherWelcome extends Widget
{
    protected string $view = 'filament.widgets.teacher-welcome';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        // Only show if user has 'Guru' role or similar check
        // Assuming strict check for now, can be adjusted based on exact role name
        return Auth::check() && Auth::user()->hasRole('Guru');
    }
}
