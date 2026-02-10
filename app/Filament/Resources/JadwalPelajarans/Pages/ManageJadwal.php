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
    public int $totalJam = 12;

    public array $jadwalData = [];

    public function mount(): void
    {
        $this->tahunAjaranId = TahunAjaran::where('is_active', true)->first()?->id;

        $allowedRombelIds = JadwalPelajaranResource::getAllowedRombelIds(auth()->user());
        if ($allowedRombelIds !== null) {
            $this->rombelId = $allowedRombelIds->first();
        }

        $this->loadJadwal();
    }

    public function tambahJam(): void
    {
        $this->totalJam++;
        $jamKe = $this->totalJam;

        // Calculate default time for new jam
        $baseMinutes = 7 * 60; // 07:00
        $slotDuration = 35;
        $startMinutes = $baseMinutes + (($jamKe - 1) * $slotDuration);
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
        $user = auth()->user();
        $allowedRombelIds = JadwalPelajaranResource::getAllowedRombelIds($user);

        return Rombel::with('kelas')
            ->whereHas('kelas')
            ->join('kelas', 'rombels.kelas_id', '=', 'kelas.id')
            ->where('kelas.nama', '!=', 'Alumni')
            ->when($allowedRombelIds !== null, function ($query) use ($allowedRombelIds) {
                return $query->whereIn('rombels.id', $allowedRombelIds);
            })
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
            $startMinutes = $baseMinutes + (($jamKe - 1) * $slotDuration);
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

            $mapelId = $jadwal?->mata_pelajaran_id;

            // Auto-fill Monday Jam 1 with Upacara Bendera (ID 46) if empty
            if ($this->selectedHari === 'Senin' && $i === 1 && !$mapelId) {
                $upacara = MataPelajaran::where('nama', 'Upacara Bendera')->first();
                if ($upacara) {
                    $mapelId = $upacara->id;
                }
            }

            $this->jadwalData[$i] = [
                'id' => $jadwal?->id,
                'mata_pelajaran_id' => $mapelId,
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
        $user = auth()->user();
        $allowedRombelIds = JadwalPelajaranResource::getAllowedRombelIds($user);

        if (!$this->tahunAjaranId || !$this->rombelId) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Pilih Tahun Ajaran dan Rombel terlebih dahulu.',
            ]);
            return;
        }

        // Authorization check
        if ($allowedRombelIds !== null && !$allowedRombelIds->contains($this->rombelId)) {
            $this->dispatch('swal:error', [
                'title' => 'Akses Ditolak!',
                'text' => 'Anda tidak memiliki akses untuk mengelola jadwal kelas ini.',
            ]);
            return;
        }

        $data = $this->jadwalData[$jamKe] ?? [];
        $mapelId = $data['mata_pelajaran_id'] ?? null;
        $teacherId = $data['teacher_id'] ?? null;

        if (!$mapelId) {
            // If mapel empty, delete existing
            if ($data['id']) {
                JadwalPelajaran::destroy($data['id']);
                $this->jadwalData[$jamKe]['id'] = null;
            }
            return;
        }

        // Special subjects that can have null teacher
        $specialSubjects = ['Upacara Bendera', 'Shalat Dluha', 'Istirahat', 'Soliskan'];
        $selectedMapel = MataPelajaran::find($mapelId);
        $isSpecialSubject = $selectedMapel && in_array($selectedMapel->nama, $specialSubjects);

        if (!$isSpecialSubject && !$teacherId) {
            // If not special and no teacher, do nothing or delete
            if ($data['id']) {
                JadwalPelajaran::destroy($data['id']);
                $this->jadwalData[$jamKe]['id'] = null;
            }
            return;
        }

        // Check teacher conflict only if teacher is assigned
        if ($teacherId) {
            $conflict = JadwalPelajaran::getTeacherConflict(
                $teacherId,
                $this->tahunAjaranId,
                $this->semester,
                $this->selectedHari,
                $jamKe,
                $data['id']
            );

            if ($conflict) {
                $teacher = Teacher::find($teacherId);
                $teacherName = $teacher?->nama_lengkap ?? 'Guru';
                $currentRombel = Rombel::with('kelas')->find($this->rombelId);
                $currentRombelName = $currentRombel?->kelas?->nama . ' - ' . $currentRombel?->nama;
                $conflictRombelName = $conflict->rombel?->kelas?->nama . ' - ' . $conflict->rombel?->nama;
                $jamMulai = $data['jam_mulai'] ?? '00:00';

                $this->dispatch('swal:error', [
                    'title' => 'Jadwal Bentrok!',
                    'text' => "{$teacherName} terjadi Jam Bentrok antara Rombel {$currentRombelName} dan {$conflictRombelName} di Jam {$jamMulai}",
                ]);
                return;
            }
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

    public function getJtmSummaryData(): array
    {
        $user = auth()->user();
        if (!$user || !$user->teacher) {
            return ['reguler' => 0, 'linier' => 0];
        }

        $teacher = $user->teacher;

        // JTM Reguler: All schedules for this teacher
        $allSchedules = JadwalPelajaran::with('mataPelajaran')
            ->where('teacher_id', $teacher->id)
            ->where('tahun_ajaran_id', $this->tahunAjaranId)
            ->where('semester', $this->semester)
            ->get();

        $jtmReguler = $allSchedules->count();

        // JTM Linier Logic
        $jtmLinier = 0;
        $isGuruKelas = $teacher->jabatan?->nama === 'Guru Kelas' || \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->exists();

        if ($isGuruKelas) {
            $linierMapels = [
                'Al Quran Hadits',
                'Akidah Akhlak',
                'Fikih',
                'Sej. Kebudayaan Islam',
                'Pendidikan Pancasila',
                'Bahasa Indonesia',
                'Matematika',
                'Seni Rupa',
                'Seni Tari',
                'Seni Musik',
                'Seni Drama'
            ];
            $jtmLinier = $allSchedules->filter(function ($s) use ($linierMapels) {
                return in_array($s->mataPelajaran?->nama, $linierMapels);
            })->count();
        } else {
            // Guru Mata Pelajaran
            $teacherMapelId = $teacher->mata_pelajaran_id;
            $teacherMainMapel = $teacher->mataPelajaran?->nama;

            $agamaMapels = ['Al Quran Hadits', 'Akidah Akhlak', 'Fikih', 'Sej. Kebudayaan Islam'];
            $isAgamaTeacher = in_array($teacherMainMapel, $agamaMapels);

            if ($isAgamaTeacher) {
                // If religious teacher, sum all religious mapels
                $jtmLinier = $allSchedules->filter(function ($s) use ($agamaMapels) {
                    return in_array($s->mataPelajaran?->nama, $agamaMapels);
                })->count();
            } else {
                // Otherwise, sum only the same mapel
                $jtmLinier = $allSchedules->filter(function ($s) use ($teacherMapelId) {
                    return $s->mata_pelajaran_id == $teacherMapelId;
                })->count();
            }
        }

        return [
            'reguler' => $jtmReguler,
            'linier' => $jtmLinier,
        ];
    }

    public function exportExcel(): BinaryFileResponse|StreamedResponse
    {
        $user = auth()->user();
        $allowedRombelIds = JadwalPelajaranResource::getAllowedRombelIds($user);

        if (!$this->tahunAjaranId || !$this->rombelId) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Pilih Tahun Ajaran dan Rombel terlebih dahulu.',
            ]);
            return response()->streamDownload(fn() => null, 'error.xlsx');
        }

        // Authorization check
        if ($allowedRombelIds !== null && !$allowedRombelIds->contains($this->rombelId)) {
            $this->dispatch('swal:error', [
                'title' => 'Akses Ditolak!',
                'text' => 'Anda tidak memiliki akses untuk mengekspor jadwal kelas ini.',
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
        $user = auth()->user();
        $allowedRombelIds = JadwalPelajaranResource::getAllowedRombelIds($user);

        if (!$this->tahunAjaranId || !$this->rombelId) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Pilih Tahun Ajaran dan Rombel terlebih dahulu.',
            ]);
            return response()->streamDownload(fn() => null, 'error.pdf');
        }

        // Authorization check
        if ($allowedRombelIds !== null && !$allowedRombelIds->contains($this->rombelId)) {
            $this->dispatch('swal:error', [
                'title' => 'Akses Ditolak!',
                'text' => 'Anda tidak memiliki akses untuk mengekspor jadwal kelas ini.',
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
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn() => print ($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}
