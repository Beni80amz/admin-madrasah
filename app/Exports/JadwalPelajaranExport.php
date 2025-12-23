<?php

namespace App\Exports;

use App\Models\JadwalPelajaran;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use App\Models\ProfileMadrasah;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class JadwalPelajaranExport implements FromView, ShouldAutoSize, WithTitle, WithEvents
{
    protected int $tahunAjaranId;
    protected string $semester;
    protected int $rombelId;

    public function __construct(int $tahunAjaranId, string $semester, int $rombelId)
    {
        $this->tahunAjaranId = $tahunAjaranId;
        $this->semester = $semester;
        $this->rombelId = $rombelId;
    }

    public function view(): View
    {
        $rombel = Rombel::with(['kelas', 'waliKelas'])->find($this->rombelId);
        $tahunAjaran = TahunAjaran::find($this->tahunAjaranId);
        $profile = ProfileMadrasah::first();

        $jadwals = JadwalPelajaran::with(['mataPelajaran', 'teacher'])
            ->where('tahun_ajaran_id', $this->tahunAjaranId)
            ->where('semester', $this->semester)
            ->where('rombel_id', $this->rombelId)
            ->get();

        return view('exports.jadwal-pelajaran', [
            'jadwals' => $jadwals,
            'rombel' => $rombel,
            'tahunAjaran' => $tahunAjaran,
            'semester' => $this->semester,
            'profile' => $profile,
        ]);
    }

    public function title(): string
    {
        return 'Jadwal Pelajaran';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Optional: Additional styling if needed beyond blade view
                $event->sheet->getDelegate()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            },
        ];
    }
}
