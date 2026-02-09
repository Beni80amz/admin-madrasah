<?php

namespace App\Filament\Resources\LearningJournals\Pages;

use App\Filament\Resources\LearningJournals\LearningJournalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLearningJournals extends ListRecords
{
    protected static string $resource = LearningJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
