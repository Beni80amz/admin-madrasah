<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.public')]
#[Title('Unduhan - Madrasah Portal')]
class Download extends Component
{
    public function render()
    {
        return view('livewire.download');
    }
}
