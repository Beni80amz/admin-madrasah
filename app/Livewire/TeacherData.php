<?php

namespace App\Livewire;

use App\Models\Jabatan;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;

#[Layout('components.layouts.public')]
class TeacherData extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $jabatan = '';

    public int $perPage = 5;

    public function getTeachersQueryProperty()
    {
        $query = Teacher::with(['jabatan', 'tugasPokok', 'tugasTambahan', 'mataPelajaran', 'rombelWaliKelas.kelas'])
            ->where('is_active', true);

        // Filter by search
        if (!empty($this->search)) {
            $query->where('nama_lengkap', 'like', '%' . $this->search . '%');
        }

        // Filter by jabatan
        if (!empty($this->jabatan)) {
            $query->whereHas('jabatan', function ($q) {
                $q->where('nama', $this->jabatan);
            });
        }

        return $query;
    }

    public function getTeachersProperty()
    {
        return $this->teachersQuery->paginate($this->perPage);
    }

    public function getJabatanOptionsProperty()
    {
        return Jabatan::orderBy('nama')->pluck('nama')->toArray();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->jabatan = '';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedJabatan()
    {
        $this->resetPage();
    }

    public function downloadPdf()
    {
        $teachers = $this->teachersQuery->get();
        $profile = ProfileMadrasah::first();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        $pdf = Pdf::loadView('pdf.teachers-landscape', [
            'teachers' => $teachers,
            'profile' => $profile,
            'tahunAjaran' => $tahunAjaran,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Data-Guru-Staff.pdf');
    }

    #[Title('Data Guru dan Staff')]
    public function render()
    {
        return view('livewire.teacher-data', [
            'teachers' => $this->teachers,
            'jabatanOptions' => $this->jabatanOptions,
        ]);
    }
}
