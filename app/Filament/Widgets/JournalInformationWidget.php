<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class JournalInformationWidget extends Widget
{
    protected int|string|array $columnSpan = 'full';

    public function render(): View
    {
        return view('filament.widgets.journal-information-widget');
    }
}
