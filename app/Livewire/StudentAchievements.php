<?php

namespace App\Livewire;

use App\Models\Achievement;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.public')]
class StudentAchievements extends Component
{
    #[Title('Prestasi Siswa')]
    public function render()
    {
        // Get student achievements from database
        $achievements = Achievement::where('type', 'siswa')
            ->orderBy('tahun', 'desc')
            ->orderBy('peringkat', 'asc')
            ->get();

        // Calculate statistics
        $juara1 = $achievements->where('peringkat', 1)->count();
        $juara2 = $achievements->where('peringkat', 2)->count();
        $juara3 = $achievements->where('peringkat', 3)->count();
        $total = $achievements->count();

        // Get unique categories for filter
        $categories = $achievements->pluck('kategori')->unique()->values();

        return view('livewire.student-achievements', [
            'achievements' => $achievements,
            'juara1' => $juara1,
            'juara2' => $juara2,
            'juara3' => $juara3,
            'total' => $total,
            'categories' => $categories,
        ]);
    }
}
