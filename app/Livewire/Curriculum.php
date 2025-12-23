<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\ProfileMadrasah;
use App\Models\MataPelajaran;

#[Layout('components.layouts.public')]
class Curriculum extends Component
{
    public ?ProfileMadrasah $profile = null;

    public function mount()
    {
        $this->profile = ProfileMadrasah::first();
    }

    #[Title('Kurikulum')]
    public function render()
    {
        // Fetch subjects grouped by kelompok
        $subjectsA = MataPelajaran::where('is_active', true)
            ->where('kelompok', 'A-Wajib')
            ->orderBy('id')
            ->get();

        $subjectsB = MataPelajaran::where('is_active', true)
            ->where('kelompok', 'B-Pilihan')
            ->orderBy('id')
            ->get();

        $subjectsC = MataPelajaran::where('is_active', true)
            ->where('kelompok', 'C-Muatan Lokal')
            ->orderBy('id')
            ->get();

        $subjectsKokurikuler = MataPelajaran::where('is_active', true)
            ->where('kelompok', 'Kokurikuler')
            ->orderBy('id')
            ->get();

        return view('livewire.curriculum', [
            'profile' => $this->profile,
            'subjectsA' => $subjectsA,
            'subjectsB' => $subjectsB,
            'subjectsC' => $subjectsC,
            'subjectsKokurikuler' => $subjectsKokurikuler,
        ]);
    }
}
