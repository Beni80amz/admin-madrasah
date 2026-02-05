<?php

namespace App\Filament\Resources\UserAccounts\Pages;

use App\Filament\Resources\UserAccounts\UserAccountResource;
use Filament\Resources\Pages\EditRecord;

class EditUserAccount extends EditRecord
{
    protected static string $resource = UserAccountResource::class;
}
