<?php

namespace App\Livewire;

use App\Models\OperationalHour;
use App\Models\ProfileMadrasah;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.public')]
class Contact extends Component
{
    #[Title('Kontak')]
    public function render()
    {
        $profile = ProfileMadrasah::getActive();

        return view('livewire.contact', [
            'operationalHours' => OperationalHour::active()->get(),
            'profile' => $profile,
        ]);
    }
}
