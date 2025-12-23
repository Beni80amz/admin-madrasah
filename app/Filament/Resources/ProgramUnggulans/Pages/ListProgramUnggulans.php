<?php

namespace App\Filament\Resources\ProgramUnggulans\Pages;

use App\Filament\Resources\ProgramUnggulans\ProgramUnggulanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramUnggulans extends ListRecords
{
    protected static string $resource = ProgramUnggulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

