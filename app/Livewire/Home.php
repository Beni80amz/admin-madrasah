<?php

namespace App\Livewire;

use App\Models\Alumni;
use App\Models\Ekstrakurikuler;
use App\Models\Fasilitas;
use App\Models\Gallery;
use App\Models\HeroSlider;
use App\Models\News;
use App\Models\ProgramUnggulan;
use App\Models\ProfileMadrasah;
use App\Models\Rombel;
use App\Models\Student;
use App\Models\TahunAjaran;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.public')]
#[Title('Beranda')]
class Home extends Component
{
    public function render()
    {
        // Stats from database
        $totalSiswa = Student::where('is_active', true)->count();
        $totalGuru = Teacher::where('is_active', true)->count();

        $activeTahunAjaran = TahunAjaran::getActive();
        $totalKelas = Rombel::where('nama', '!=', 'Alumni')
            ->when($activeTahunAjaran, function ($query, $tahunAjaran) {
                return $query->where('tahun_ajaran_id', $tahunAjaran->id);
            })
            ->count();

        $totalAlumni = Alumni::count();

        // Hero sliders
        $heroSliders = HeroSlider::active()->ordered()->get();

        return view('livewire.home', [
            'heroSliders' => $heroSliders,
            'programs' => ProgramUnggulan::active()->ordered()->get(),
            'fasilitas' => Fasilitas::active()->ordered()->get(),
            'ekstrakurikuler' => Ekstrakurikuler::active()->ordered()->get(),
            'news' => News::published()->latest()->take(3)->get(),
            'galleryPhotos' => Gallery::active()->photos()->ordered()->take(4)->get(),
            'galleryVideos' => Gallery::active()->videos()->ordered()->take(4)->get(),
            'siteProfile' => ProfileMadrasah::first(),
            // Dynamic stats
            'totalSiswa' => $totalSiswa,
            'totalGuru' => $totalGuru,
            'totalKelas' => $totalKelas,
            'totalAlumni' => $totalAlumni,
        ]);
    }
}
