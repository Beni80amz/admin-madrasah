<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getHeading(): string|Htmlable
    {
        return 'Welcome Back';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Please enter your details to access the Madrasah portal.';
    }
}
