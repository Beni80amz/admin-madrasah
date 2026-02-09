<?php

namespace App\Filament\Resources\LearningJournals\Pages;

use App\Filament\Resources\LearningJournals\LearningJournalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLearningJournal extends EditRecord
{
    protected static string $resource = LearningJournalResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\JournalInformationWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
