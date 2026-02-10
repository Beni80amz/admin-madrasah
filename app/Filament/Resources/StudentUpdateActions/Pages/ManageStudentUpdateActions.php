<?php

namespace App\Filament\Resources\StudentUpdateActions\Pages;

use App\Filament\Resources\StudentUpdateActions\StudentUpdateActionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStudentUpdateActions extends ManageRecords
{
    protected static string $resource = StudentUpdateActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No manual creation needed
        ];
    }
}
