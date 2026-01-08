<?php

namespace App\Filament\Resources\AttendanceSettings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class AttendanceSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->disabled()
                    ->required(),
                TextInput::make('value')
                    ->required(),
                Textarea::make('description')
                    ->rows(3),
            ]);
    }
}
