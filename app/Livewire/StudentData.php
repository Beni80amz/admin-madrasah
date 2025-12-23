<?php

namespace App\Livewire;

use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentDataExport;

#[Layout('components.layouts.public')]
class StudentData extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $kelas = '';

    public function exportExcel()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return $this->redirect(route('unauthorized', ['feature' => 'Download Data Siswa']), navigate: true);
        }

        return Excel::download(new StudentDataExport($this->search, $this->kelas), 'Data-Siswa-' . date('Y-m-d') . '.xlsx');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->kelas = '';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedKelas()
    {
        $this->resetPage();
    }

    #[Title('Data Siswa')]
    public function render()
    {
        $query = Student::query()
            ->where('is_active', true)
            ->orderBy('kelas', 'asc')
            ->orderBy('nama_lengkap', 'asc');

        // Filter by search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%')
                    ->orWhere('nis_lokal', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by kelas
        if (!empty($this->kelas)) {
            $query->where('kelas', $this->kelas);
        }

        $students = $query->paginate(50);

        // Get unique kelas for filter options
        $kelasOptions = Student::where('is_active', true)
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas', 'asc')
            ->pluck('kelas')
            ->toArray();

        // Get total student count
        $totalStudents = Student::where('is_active', true)->count();

        return view('livewire.student-data', [
            'paginatedStudents' => $students,
            'kelasOptions' => $kelasOptions,
            'totalStudents' => $totalStudents,
        ]);
    }
}
