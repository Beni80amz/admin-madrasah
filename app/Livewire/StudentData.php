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
use App\Traits\HasExportPassword;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;

#[Layout('components.layouts.public')]
class StudentData extends Component
{
    use WithPagination, HasExportPassword;

    #[Url]
    public string $search = '';

    #[Url]
    public string $kelas = '';

    public function exportExcel()
    {
        return $this->openExportModal('excel');
    }

    public function downloadPdf()
    {
        return $this->openExportModal('pdf');
    }

    protected function executeExport()
    {
        if ($this->exportType === 'excel') {
            return Excel::download(new StudentDataExport($this->search, $this->kelas), 'Data-Siswa-' . date('Y-m-d') . '.xlsx');
        }

        if ($this->exportType === 'pdf') {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '300');

            $siteProfile = ProfileMadrasah::first();
            $tahunAjaran = TahunAjaran::where('is_active', true)->first();

            $students = Student::where('is_active', true)
                ->when($this->search, function ($q) {
                    $q->where(function ($inner) {
                        $inner->where('nama_lengkap', 'like', '%' . $this->search . '%')
                            ->orWhere('nisn', 'like', '%' . $this->search . '%')
                            ->orWhere('nis_lokal', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->kelas, fn($q) => $q->where('kelas', $this->kelas))
                ->orderBy('kelas', 'asc')
                ->orderBy('nama_lengkap', 'asc')
                ->get();

            $total = $students->count();

            $byKelas = $students->groupBy('kelas')
                ->map(fn($items) => $items->count())
                ->sortKeys();

            $data = [
                'siteProfile' => $siteProfile,
                'tahunAjaran' => $tahunAjaran,
                'students' => $students,
                'total' => $total,
                'byKelas' => $byKelas,
            ];

            $pdf = Pdf::loadView('pdf.students', $data);
            $pdf->setPaper('A4', 'portrait');

            $filename = 'Data-Siswa-' . (optional($siteProfile)->nama_madrasah ?? 'Madrasah') . '.pdf';
            $filename = str_replace(['/', '\\'], '-', $filename);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename);
        }
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
