<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        $schoolName = \App\Models\ProfileMadrasah::first()?->nama_madrasah ?? 'Madrasah';

        return new \Illuminate\Support\HtmlString("
            <div class='flex flex-col items-center gap-1'>
                <span class='text-sm font-medium text-[#20c997]'>$schoolName</span>
                <span class='text-2xl font-bold tracking-tight'>Welcome Back</span>
            </div>
        ");
    }

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'Please enter your details to access the Madrasah portal.';
    }
}
