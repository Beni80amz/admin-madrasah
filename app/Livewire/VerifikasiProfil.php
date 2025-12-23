<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProfileMadrasah;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.public')]
#[Title('Verifikasi Profil Madrasah')]
class VerifikasiProfil extends Component
{
    public $profile;

    public function mount()
    {
        $this->profile = ProfileMadrasah::first();
    }

    public function render()
    {
        return view('livewire.verifikasi-profil');
    }
}
