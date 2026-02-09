<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class JournalInformationWidget extends Widget
{
    protected string $view = 'filament.widgets.journal-information-widget';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return !request()->routeIs('filament.admin.pages.dashboard');
    }
}
