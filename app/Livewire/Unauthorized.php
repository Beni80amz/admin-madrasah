<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.public')]
class Unauthorized extends Component
{
    public string $message = '';
    public string $feature = '';

    public function mount($feature = 'fitur ini', $message = '')
    {
        $this->feature = $feature;
        $this->message = $message ?: "Anda tidak diperkenankan mengakses {$feature} sebelum login terlebih dahulu.";
    }

    #[Title('Akses Terbatas')]
    public function render()
    {
        return view('livewire.unauthorized');
    }
}
