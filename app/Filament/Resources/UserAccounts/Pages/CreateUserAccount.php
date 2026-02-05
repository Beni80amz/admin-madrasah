<?php

namespace App\Filament\Resources\UserAccounts\Pages;

use App\Filament\Resources\UserAccounts\UserAccountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserAccount extends CreateRecord
{
    protected static string $resource = UserAccountResource::class;
}
