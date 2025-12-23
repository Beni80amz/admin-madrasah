<?php

namespace App\Filament\Pages;

use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Student;
use App\Models\TahunAjaran;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use UnitEnum;

class ManageKenaikanKelas extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-trending-up';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('Superadmin');
    }
    protected static ?string $navigationLabel = 'Kenaikan Kelas';
    protected static ?string $title = 'Kenaikan Kelas';
    protected static ?string $slug = 'kenaikan-kelas';
    protected static UnitEnum|string|null $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 6;

    public function getView(): string
    {
        return 'filament.pages.manage-kenaikan-kelas';
    }

    // Source filters
    public ?string $tahunAjaranAsal = null;
    public ?string $tingkatAsal = null;
    public ?string $kelasAsal = null;
    public string $searchAsal = '';

    // Target filters
    public ?string $tahunAjaranTujuan = null;
    public ?string $tingkatTujuan = null;
    public ?string $kelasTujuan = null;

    // Selected students
    public array $selectedStudents = [];

    // Pagination
    public int $perPage = 10;
    public int $currentPage = 1;

    public function mount(): void
    {
        // Set default tahun ajaran asal to active one
        $activeTahunAjaran = TahunAjaran::where('is_active', true)->first();
        if ($activeTahunAjaran) {
            $this->tahunAjaranAsal = (string) $activeTahunAjaran->id;

            // Auto-select next tahun ajaran for tujuan
            $nextTahunAjaran = TahunAjaran::where('id', '>', $activeTahunAjaran->id)
                ->orderBy('id')
                ->first();
            if ($nextTahunAjaran) {
                $this->tahunAjaranTujuan = (string) $nextTahunAjaran->id;
            }
        }
    }

    public function getTahunAjaranOptions(): array
    {
        return TahunAjaran::orderBy('nama', 'desc')
            ->pluck('nama', 'id')
            ->toArray();
    }

    public function getTingkatOptions(): array
    {
        return Kelas::orderBy('tingkat')
            ->get()
            ->mapWithKeys(function ($kelas) {
                $tingkat = $this->romanToArabic($kelas->tingkat);
                return [$tingkat => 'Tingkat ' . $tingkat];
            })
            ->unique()
            ->toArray();
    }

    public function getKelasAsalOptions(): array
    {
        if (!$this->tingkatAsal) {
            return [];
        }

        return Rombel::with('kelas')
            ->whereHas('kelas', function ($query) {
                $query->where('tingkat', $this->tingkatAsal)
                    ->orWhere('tingkat', $this->arabicToRoman((int) $this->tingkatAsal));
            })
            ->when($this->tahunAjaranAsal, function ($query) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranAsal);
            })
            ->get()
            ->mapWithKeys(function ($rombel) {
                $tingkat = $this->romanToArabic($rombel->kelas?->tingkat ?? '');
                $value = $tingkat . '-' . $rombel->nama;
                return [$value => $value];
            })
            ->toArray();
    }

    public function getKelasTujuanOptions(): array
    {
        if (!$this->tingkatTujuan) {
            return [];
        }

        return Rombel::with('kelas')
            ->whereHas('kelas', function ($query) {
                $query->where('tingkat', $this->tingkatTujuan)
                    ->orWhere('tingkat', $this->arabicToRoman((int) $this->tingkatTujuan));
            })
            ->when($this->tahunAjaranTujuan, function ($query) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranTujuan);
            })
            ->get()
            ->mapWithKeys(function ($rombel) {
                $tingkat = $this->romanToArabic($rombel->kelas?->tingkat ?? '');
                $value = $tingkat . '-' . $rombel->nama;
                return [$value => $value];
            })
            ->toArray();
    }

    public function getStudentsAsal(): Collection
    {
        if (!$this->kelasAsal) {
            return collect();
        }

        $query = Student::where('is_active', true)
            ->where('kelas', $this->kelasAsal)
            ->whereNotIn('id', $this->selectedStudents)
            ->orderBy('nama_lengkap');

        // Filter by tahun ajaran if selected
        if ($this->tahunAjaranAsal) {
            $query->where(function ($q) {
                $q->where('tahun_ajaran_id', $this->tahunAjaranAsal)
                    ->orWhereNull('tahun_ajaran_id');
            });
        }

        if ($this->searchAsal) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->searchAsal . '%')
                    ->orWhere('nisn', 'like', '%' . $this->searchAsal . '%');
            });
        }

        return $query->get();
    }

    public function getStudentsTujuan(): Collection
    {
        if (empty($this->selectedStudents)) {
            return collect();
        }

        return Student::whereIn('id', $this->selectedStudents)
            ->orderBy('nama_lengkap')
            ->get();
    }

    public function selectStudent(int $studentId): void
    {
        if (!in_array($studentId, $this->selectedStudents)) {
            $this->selectedStudents[] = $studentId;
        }
    }

    public function deselectStudent(int $studentId): void
    {
        $this->selectedStudents = array_values(array_filter(
            $this->selectedStudents,
            fn($id) => $id !== $studentId
        ));
    }

    public function selectAll(): void
    {
        $students = $this->getStudentsAsal();
        foreach ($students as $student) {
            if (!in_array($student->id, $this->selectedStudents)) {
                $this->selectedStudents[] = $student->id;
            }
        }
    }

    public function clearSelection(): void
    {
        $this->selectedStudents = [];
    }

    public function processKenaikanKelas(): void
    {
        if (empty($this->selectedStudents)) {
            Notification::make()
                ->title('Error')
                ->body('Pilih siswa yang akan dinaikkan terlebih dahulu.')
                ->danger()
                ->send();
            return;
        }

        if (!$this->kelasTujuan) {
            Notification::make()
                ->title('Error')
                ->body('Pilih kelas tujuan terlebih dahulu.')
                ->danger()
                ->send();
            return;
        }

        $count = count($this->selectedStudents);
        $isAlumni = str_contains(strtolower($this->kelasTujuan), 'alumni');

        if ($isAlumni) {
            // Get tahun lulus from target tahun ajaran
            $tahunAjaran = TahunAjaran::find($this->tahunAjaranTujuan);
            $tahunLulus = $tahunAjaran ? explode('/', $tahunAjaran->nama)[0] : date('Y');

            // Get students and create alumni records
            $students = Student::whereIn('id', $this->selectedStudents)->get();

            foreach ($students as $student) {
                \App\Models\Alumni::create([
                    'photo' => $student->photo,
                    'nama_lengkap' => $student->nama_lengkap,
                    'tahun_lulus' => $tahunLulus,
                    'alamat' => $student->alamat_domisili ?? $student->alamat_kk,
                    'nomor_mobile' => $student->nomor_mobile,
                ]);
            }

            // Deactivate students
            Student::whereIn('id', $this->selectedStudents)
                ->update([
                    'is_active' => false,
                    'kelas' => $this->kelasTujuan,
                    'tahun_ajaran_id' => $this->tahunAjaranTujuan,
                ]);

            // SweetAlert success for alumni
            $this->dispatch('swal:success', [
                'title' => 'Berhasil!',
                'text' => "{$count} siswa berhasil lulus dan dipindahkan ke data Alumni.",
            ]);
        } else {
            // Normal promotion - update students with new kelas and tahun ajaran
            Student::whereIn('id', $this->selectedStudents)
                ->update([
                    'kelas' => $this->kelasTujuan,
                    'tahun_ajaran_id' => $this->tahunAjaranTujuan,
                ]);

            // SweetAlert success
            $this->dispatch('swal:success', [
                'title' => 'Berhasil!',
                'text' => "{$count} siswa berhasil dinaikkan ke kelas {$this->kelasTujuan}.",
            ]);
        }

        // Clear selection
        $this->selectedStudents = [];
    }

    public function updatedTingkatAsal(): void
    {
        $this->kelasAsal = null;
    }

    public function updatedTingkatTujuan(): void
    {
        $this->kelasTujuan = null;
    }

    private function romanToArabic(string $roman): string
    {
        $romans = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100];
        $roman = strtoupper(trim($roman));

        if (is_numeric($roman)) {
            return $roman;
        }

        $result = 0;
        $length = strlen($roman);
        for ($i = 0; $i < $length; $i++) {
            $current = $romans[$roman[$i]] ?? 0;
            $next = ($i + 1 < $length) ? ($romans[$roman[$i + 1]] ?? 0) : 0;
            if ($current < $next) {
                $result -= $current;
            } else {
                $result += $current;
            }
        }
        return $result > 0 ? (string) $result : $roman;
    }

    private function arabicToRoman(int $number): string
    {
        $map = [
            100 => 'C',
            90 => 'XC',
            50 => 'L',
            40 => 'XL',
            10 => 'X',
            9 => 'IX',
            5 => 'V',
            4 => 'IV',
            1 => 'I'
        ];
        $result = '';
        foreach ($map as $value => $numeral) {
            while ($number >= $value) {
                $result .= $numeral;
                $number -= $value;
            }
        }
        return $result;
    }
}
