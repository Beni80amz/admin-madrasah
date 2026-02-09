<?php

namespace App\Filament\Resources\LearningJournals\Pages;

use App\Filament\Resources\LearningJournals\LearningJournalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLearningJournal extends CreateRecord
{
    protected static string $resource = LearningJournalResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\LJInstructionWidget::class,
        ];
    }
}
