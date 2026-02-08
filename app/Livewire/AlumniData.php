<?php

namespace App\Livewire;

use App\Models\Alumni;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;
use App\Traits\HasExportPassword;

#[Layout('components.layouts.public')]
class AlumniData extends Component
{
    use WithPagination, HasExportPassword;

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

    public function downloadPdf()
    {
        return $this->openExportModal('pdf');
    }

    protected function executeExport()
    {
        if ($this->exportType === 'pdf') {
            $siteProfile = ProfileMadrasah::first();
            $tahunAjaran = TahunAjaran::where('is_active', true)->first();

            $alumni = Alumni::when($this->search, function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%');
            })
                ->when($this->tahunLulus, fn($q) => $q->where('tahun_lulus', $this->tahunLulus))
                ->orderBy('tahun_lulus', 'desc')
                ->orderBy('nama_lengkap', 'asc')
                ->get();

            $total = $alumni->count();

            $byYear = $alumni->groupBy('tahun_lulus')
                ->map(fn($items) => $items->count())
                ->sortKeysDesc();

            $data = [
                'siteProfile' => $siteProfile,
                'tahunAjaran' => $tahunAjaran,
                'alumni' => $alumni,
                'total' => $total,
                'byYear' => $byYear,
                'qrCodeImage' => 'data:image/png;base64,' . base64_encode(app(\App\Services\QrCodeService::class)->generateDocumentVerificationQrCode()),
            ];

            $pdf = Pdf::loadView('pdf.alumni', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions(['isRemoteEnabled' => true]);

            $filename = 'Data-Alumni-' . ($siteProfile->nama_madrasah ?? 'Madrasah') . '.pdf';
            $filename = str_replace(['/', '\\'], '-', $filename);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename);
        }
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
