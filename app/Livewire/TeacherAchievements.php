<?php

namespace App\Livewire;

use App\Models\Achievement;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.public')]
class TeacherAchievements extends Component
{
    #[Title('Prestasi Guru')]
    public function render()
    {
        // Get teacher achievements from database
        $achievements = Achievement::where('type', 'guru')
            ->orderBy('tahun', 'desc')
            ->orderBy('peringkat', 'asc')
            ->get();

        // Get teacher photos for fallback
        // Fetch all teachers to ensure case-insensitive matching in PHP works correctly
        $teachers = \App\Models\Teacher::all();

        // Attach fallback photo
        $achievements->transform(function ($achievement) use ($teachers) {
            $achievement->photo_url = null;

            if ($achievement->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($achievement->photo)) {
                $achievement->photo_url = $achievement->photo;
            }

            if (!$achievement->photo_url) {
                // Find matching teacher (insensitive & trim)
                $teacher = $teachers->first(function ($t) use ($achievement) {
                    return strtolower(trim($t->nama_lengkap)) === strtolower(trim($achievement->nama));
                });

                if ($teacher && $teacher->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($teacher->photo)) {
                    $achievement->photo_url = $teacher->photo;
                }
            }
            return $achievement;
        });

        // Calculate statistics
        $juara1 = $achievements->where('peringkat', 1)->count();
        $juara2 = $achievements->where('peringkat', 2)->count();
        $juara3 = $achievements->where('peringkat', 3)->count();
        $total = $achievements->count();

        // Get unique categories for filter
        $categories = $achievements->pluck('kategori')->unique()->values();

        return view('livewire.teacher-achievements', [
            'achievements' => $achievements,
            'juara1' => $juara1,
            'juara2' => $juara2,
            'juara3' => $juara3,
            'total' => $total,
            'categories' => $categories,
        ]);
    }
}
