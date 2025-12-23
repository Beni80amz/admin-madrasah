<?php

namespace App\Livewire;

use App\Models\Alumni;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('components.layouts.public')]
class AlumniData extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $tahunLulus = '';

    public function maskPhoneNumber($phone)
    {
        if ($phone && strlen($phone) > 6) {
            return substr($phone, 0, 4) . '****' . substr($phone, -4);
        }
        return '****';
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->tahunLulus = '';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTahunLulus()
    {
        $this->resetPage();
    }

    #[Title('Data Alumni')]
    public function render()
    {
        $query = Alumni::query()
            ->orderBy('tahun_lulus', 'desc')
            ->orderBy('nama_lengkap', 'asc');

        // Filter by search
        if (!empty($this->search)) {
            $query->where('nama_lengkap', 'like', '%' . $this->search . '%');
        }

        // Filter by tahun lulus
        if (!empty($this->tahunLulus)) {
            $query->where('tahun_lulus', $this->tahunLulus);
        }

        $alumni = $query->paginate(10);

        // Get unique tahun lulus for filter options
        $tahunLulusOptions = Alumni::select('tahun_lulus')
            ->distinct()
            ->orderBy('tahun_lulus', 'desc')
            ->pluck('tahun_lulus')
            ->toArray();

        // Get total alumni count
        $totalAlumni = Alumni::count();

        return view('livewire.alumni-data', [
            'paginatedAlumni' => $alumni,
            'tahunLulusOptions' => $tahunLulusOptions,
            'totalAlumni' => $totalAlumni,
        ]);
    }
}
