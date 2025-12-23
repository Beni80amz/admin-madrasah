<?php

namespace App\Filament\Resources\JadwalPelajarans\Pages;

use App\Exports\JadwalPelajaranExport;
use App\Filament\Resources\JadwalPelajarans\JadwalPelajaranResource;
use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use App\Models\Teacher;
use App\Models\ProfileMadrasah;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManageJadwal extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = JadwalPelajaranResource::class;

    protected static ?string $title = 'Kelola Jadwal Pelajaran';

    public function getView(): string
    {
        return 'filament.resources.jadwal-pelajarans.pages.manage-jadwal';
    }

    public ?int $tahunAjaranId = null;
    public ?string $semester = 'ganjil';
    public ?int $rombelId = null;
    public string $selectedHari = 'Senin';
    public int $totalJam = 8;

    public array $jadwalData = [];

    public function mount(): void
    {
        $this->tahunAjaranId = TahunAjaran::where('is_active', true)->first()?->id;
        $this->loadJadwal();
    }

    public function tambahJam(): void
    {
        $this->totalJam++;
        $jamKe = $this->totalJam;

        // Calculate default time for new jam
        $baseMinutes = 7 * 60;
        $slotDuration = 35;
        $breakAfter4 = 15;
        $startMinutes = $baseMinutes + (($jamKe - 1) * $slotDuration);
        if ($jamKe > 4) {
            $startMinutes += $breakAfter4;
        }
        $endMinutes = $startMinutes + $slotDuration;

        $this->jadwalData[$this->totalJam] = [
            'id' => null,
            'mata_pelajaran_id' => null,
            'teacher_id' => null,
            'jam_mulai' => sprintf('%02d:%02d', floor($startMinutes / 60), $startMinutes % 60),
            'jam_selesai' => sprintf('%02d:%02d', floor($endMinutes / 60), $endMinutes % 60),
        ];
    }

    public function kurangiJam(): void
    {
        if ($this->totalJam > 1) {
            $jamKe = $this->totalJam;
            // Delete the jadwal if exists
            $lastJam = $this->jadwalData[$this->totalJam] ?? null;
            if ($lastJam && $lastJam['id']) {
                JadwalPelajaran::destroy($lastJam['id']);
            }
            unset($this->jadwalData[$this->totalJam]);
            $this->totalJam--;

            $this->dispatch('swal:success', [
                'title' => 'Jam Dikurangi!',
                'text' => "Jam ke-{$jamKe} berhasil dihapus.",
            ]);
        }
    }

    public function getHariOptions(): array
    {
        return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    }

    public function getTahunAjaranOptions(): array
    {
        return TahunAjaran::pluck('nama', 'id')->toArray();
    }

    public function getSemesterOptions(): array
    {
        return [
            'ganjil' => 'Ganjil',
            'genap' => 'Genap',
        ];
    }

    public function getRombelOptions(): array
    {
        return Rombel::with('kelas')
            ->whereHas('kelas')
            ->join('kelas', 'rombels.kelas_id', '=', 'kelas.id')
            ->where('kelas.nama', '!=', 'Alumni')
            ->orderBy('kelas.tingkat')
            ->orderBy('rombels.nama')
            ->select('rombels.*')
            ->get()
            ->mapWithKeys(function ($rombel) {
                $tingkat = $rombel->kelas?->nama ?? '';
                $label = 'Kelas ' . $tingkat . ' - ' . $rombel->nama;
                return [$rombel->id => $label];
            })
            ->toArray();
    }

    public function getMataPelajaranOptions(): array
    {
        return MataPelajaran::where('is_active', true)
            ->pluck('nama', 'id')
            ->toArray();
    }

    public function getTeacherOptions(): array
    {
        return Teacher::where('is_active', true)
            ->pluck('nama_lengkap', 'id')
            ->toArray();
    }

    public function selectHari(string $hari): void
    {
        $this->selectedHari = $hari;
        $this->loadJadwal();
    }

    public function loadJadwal(): void
    {
        $this->jadwalData = [];

        // Default time slots for new entries
        $getDefaultTime = function ($jamKe) {
            $baseMinutes = 7 * 60; // 07:00
            $slotDuration = 35;
            $breakAfter4 = 15;
            $startMinutes = $baseMinutes + (($jamKe - 1) * $slotDuration);
            if ($jamKe > 4) {
                $startMinutes += $breakAfter4;
            }
            $endMinutes = $startMinutes + $slotDuration;
            return [
                sprintf('%02d:%02d', floor($startMinutes / 60), $startMinutes % 60),
                sprintf('%02d:%02d', floor($endMinutes / 60), $endMinutes % 60),
            ];
        };

        if (!$this->tahunAjaranId || !$this->rombelId) {
            // Initialize empty slots
            for ($i = 1; $i <= $this->totalJam; $i++) {
                $defaultTime = $getDefaultTime($i);
                $this->jadwalData[$i] = [
                    'id' => null,
                    'mata_pelajaran_id' => null,
                    'teacher_id' => null,
                    'jam_mulai' => $defaultTime[0],
                    'jam_selesai' => $defaultTime[1],
                ];
            }
            return;
        }

        // Load existing jadwal
        $jadwals = JadwalPelajaran::where('tahun_ajaran_id', $this->tahunAjaranId)
            ->where('semester', $this->semester)
            ->where('rombel_id', $this->rombelId)
            ->where('hari', $this->selectedHari)
            ->get()
            ->keyBy('jam_ke');

        // Auto-detect max jam from existing data
        $maxJam = $jadwals->keys()->max() ?: 8;
        $this->totalJam = max($this->totalJam, $maxJam);

        for ($i = 1; $i <= $this->totalJam; $i++) {
            $jadwal = $jadwals->get($i);
            $defaultTime = $getDefaultTime($i);
            $this->jadwalData[$i] = [
                'id' => $jadwal?->id,
                'mata_pelajaran_id' => $jadwal?->mata_pelajaran_id,
                'teacher_id' => $jadwal?->teacher_id,
                'jam_mulai' => $jadwal?->jam_mulai ? substr($jadwal->jam_mulai, 0, 5) : $defaultTime[0],
                'jam_selesai' => $jadwal?->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : $defaultTime[1],
            ];
        }
    }

    public function updatedTahunAjaranId(): void
    {
        $this->loadJadwal();
    }

    public function updatedSemester(): void
    {
        $this->loadJadwal();
    }

    public function updatedRombelId(): void
    {
        $this->loadJadwal();
    }

    public function saveJamKe(int $jamKe): void
    {
        if (!$this->tahunAjaranId || !$this->rombelId) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Pilih Tahun Ajaran dan Rombel terlebih dahulu.',
            ]);
            return;
        }

        $data = $this->jadwalData[$jamKe] ?? [];
        $mapelId = $data['mata_pelajaran_id'] ?? null;
        $teacherId = $data['teacher_id'] ?? null;

        if (!$mapelId || !$teacherId) {
            // If both empty, delete existing
            if ($data['id']) {
                JadwalPelajaran::destroy($data['id']);
                $this->jadwalData[$jamKe]['id'] = null;
            }
            return;
        }

        // Check teacher conflict
        $conflict = JadwalPelajaran::getTeacherConflict(
            $teacherId,
            $this->tahunAjaranId,
            $this->semester,
            $this->selectedHari,
            $jamKe,
            $data['id']
        );

        if ($conflict) {
            $rombelName = $conflict->rombel?->kelas?->nama . ' - ' . $conflict->rombel?->nama;
            $this->dispatch('swal:error', [
                'title' => 'Jadwal Bentrok!',
                'text' => "Guru sudah mengajar di {$rombelName} pada waktu yang sama.",
            ]);
            return;
        }

        // Get time from manual input
        $jamMulai = $data['jam_mulai'] ?? '07:00';
        $jamSelesai = $data['jam_selesai'] ?? '07:35';

        $jadwal = JadwalPelajaran::updateOrCreate(
            [
                'tahun_ajaran_id' => $this->tahunAjaranId,
                'semester' => $this->semester,
                'rombel_id' => $this->rombelId,
                'hari' => $this->selectedHari,
                'jam_ke' => $jamKe,
            ],
            [
                'mata_pelajaran_id' => $mapelId,
                'teacher_id' => $teacherId,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'is_active' => true,
            ]
        );

        $this->jadwalData[$jamKe]['id'] = $jadwal->id;
    }

    public function resetJamKe(int $jamKe): void
    {
        // Delete existing jadwal if any
        $existingId = $this->jadwalData[$jamKe]['id'] ?? null;
        if ($existingId) {
            JadwalPelajaran::destroy($existingId);
        }

        $this->jadwalData[$jamKe] = [
            'id' => null,
            'mata_pelajaran_id' => null,
            'teacher_id' => null,
            'jam_mulai' => $this->jadwalData[$jamKe]['jam_mulai'] ?? '07:00',
            'jam_selesai' => $this->jadwalData[$jamKe]['jam_selesai'] ?? '07:35',
        ];

        $this->dispatch('swal:success', [
            'title' => 'Jam Di-reset!',
            'text' => "Jadwal jam ke-{$jamKe} berhasil di-reset.",
        ]);
    }

    public function hapusJamKe(int $jamKe): void
    {
        $id = $this->jadwalData[$jamKe]['id'] ?? null;
        if ($id) {
            JadwalPelajaran::destroy($id);
            $this->dispatch('swal:success', [
                'title' => 'Jadwal Dihapus!',
                'text' => "Jadwal jam ke-{$jamKe} berhasil dihapus.",
            ]);
        }

        $this->jadwalData[$jamKe] = [
            'id' => null,
            'mata_pelajaran_id' => null,
            'teacher_id' => null,
        ];
    }

    public function getGuruMapelData(): array
    {
        if (!$this->rombelId) {
            return [];
        }

        // Get all teachers assigned to teach in this rombel's jadwal
        return JadwalPelajaran::with(['mataPelajaran', 'teacher'])
            ->where('tahun_ajaran_id', $this->tahunAjaranId)
            ->where('semester', $this->semester)
            ->where('rombel_id', $this->rombelId)
            ->get()
            ->unique('teacher_id')
            ->map(function ($jadwal) {
                return [
                    'mapel' => $jadwal->mataPelajaran?->nama ?? '-',
                    'guru' => $jadwal->teacher?->nama_lengkap ?? '-',
                    'jtm' => JadwalPelajaran::where('teacher_id', $jadwal->teacher_id)
                        ->where('tahun_ajaran_id', $this->tahunAjaranId)
                        ->where('semester', $this->semester)
                        ->where('rombel_id', $this->rombelId)
                        ->count(),
                ];
            })
            ->values()
            ->toArray();
    }

    public function exportExcel(): BinaryFileResponse|StreamedResponse
    {
        if (!$this->tahunAjaranId || !$this->rombelId) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Pilih Tahun Ajaran dan Rombel terlebih dahulu.',
            ]);
            return response()->streamDownload(fn() => null, 'error.xlsx');
        }

        $rombel = Rombel::with('kelas')->find($this->rombelId);
        $kelasNama = $rombel?->kelas?->nama ?? '';
        $rombelNama = $rombel?->nama ?? '';
        $filename = "Jadwal_Kelas_{$kelasNama}_{$rombelNama}_" . ucfirst($this->semester) . ".xlsx";

        return Excel::download(
            new JadwalPelajaranExport($this->tahunAjaranId, $this->semester, $this->rombelId),
            $filename
        );
    }

    public function exportPdf(): StreamedResponse
    {
        if (!$this->tahunAjaranId || !$this->rombelId) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Pilih Tahun Ajaran dan Rombel terlebih dahulu.',
            ]);
            return response()->streamDownload(fn() => null, 'error.pdf');
        }

        $rombel = Rombel::with(['kelas', 'waliKelas'])->find($this->rombelId);
        $tahunAjaran = TahunAjaran::find($this->tahunAjaranId);
        $profile = ProfileMadrasah::first();

        $jadwals = JadwalPelajaran::with(['mataPelajaran', 'teacher'])
            ->where('tahun_ajaran_id', $this->tahunAjaranId)
            ->where('semester', $this->semester)
            ->where('rombel_id', $this->rombelId)
            ->orderBy('hari')
            ->orderBy('jam_ke')
            ->get();

        $kelasNama = $rombel?->kelas?->nama ?? '';
        $rombelNama = $rombel?->nama ?? '';
        $filename = "Jadwal_Kelas_{$kelasNama}_{$rombelNama}_" . ucfirst($this->semester) . ".pdf";

        $pdf = Pdf::loadView('pdf.jadwal-pelajaran', [
            'jadwals' => $jadwals,
            'rombel' => $rombel,
            'tahunAjaran' => $tahunAjaran,
            'semester' => $this->semester,
            'profile' => $profile,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print ($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}
