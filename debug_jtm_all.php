<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teachers = App\Models\Teacher::with(['mataPelajaran', 'jabatan'])->get();
$nonKbmMapels = ['Istirahat', 'Soliskan', 'Shalat Dluha', '7 Kebiasaan Anak Indonesia Hebat'];
$agamaMapels = ['Al Quran Hadits', 'Akidah Akhlak', 'Fikih', 'S K I', 'Sejarah Kebudayaan Islam'];

echo "JTM DEBUG FOR ALL TEACHERS\n";
echo str_repeat("=", 80) . "\n";

foreach ($teachers as $teacher) {
    // Basic Info
    $teacherMainMapel = $teacher->mataPelajaran?->nama ?? 'NONE';
    $teacherMapelId = $teacher->mata_pelajaran_id;
    $isWaliKelas = \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->exists();
    $isGuruKelas = $teacher->jabatan?->nama === 'Guru Kelas' || $isWaliKelas;

    // Schedules
    $allSchedules = App\Models\JadwalPelajaran::with('mataPelajaran')
        ->where('teacher_id', $teacher->id)
        ->get()
        ->filter(function ($s) use ($nonKbmMapels) {
            return !in_array($s->mataPelajaran?->nama, $nonKbmMapels);
        });

    $jtmReguler = $allSchedules->count();
    if ($jtmReguler == 0)
        continue; // Skip teachers without schedules for brevity

    $jtmLinier = 0;
    if ($isGuruKelas) {
        $linierMapels = [
            'Al Quran Hadits',
            'Akidah Akhlak',
            'Fikih',
            'S K I',
            'Sejarah Kebudayaan Islam',
            'Pend. Pancasila',
            'Pendidikan Pancasila',
            'Bhs. Indonesia',
            'Bahasa Indonesia',
            'Matematika',
            'I P A S',
            'Ilmu Pengetahuan Alam & Sosial',
            'Seni Rupa',
            'Seni Tari',
            'Seni Musik',
            'Seni Drama'
        ];
        $jtmLinier = $allSchedules->filter(function ($s) use ($linierMapels) {
            return in_array($s->mataPelajaran?->nama, $linierMapels);
        })->count();
        $role = "Guru Kelas / Wali";
    } else {
        $isAgamaTeacher = in_array($teacherMainMapel, $agamaMapels);
        if ($isAgamaTeacher) {
            $jtmLinier = $allSchedules->filter(function ($s) use ($agamaMapels) {
                return in_array($s->mataPelajaran?->nama, $agamaMapels);
            })->count();
            $role = "Guru Mapel Agama ($teacherMainMapel)";
        } else {
            $jtmLinier = $allSchedules->filter(function ($s) use ($teacherMapelId) {
                return $s->mata_pelajaran_id == $teacherMapelId;
            })->count();
            $role = "Guru Mapel Umum ($teacherMainMapel)";
        }
    }

    echo sprintf(
        "Teacher: %-30s | Role: %-30s | Reg: %-2d | Linier: %-2d\n",
        $teacher->nama_lengkap,
        $role,
        $jtmReguler,
        $jtmLinier
    );

    if ($jtmLinier == 0 && $jtmReguler > 0) {
        echo "  Check details:\n";
        foreach ($allSchedules as $s) {
            echo "    - Mapel: " . $s->mataPelajaran?->nama . " (ID: " . $s->mata_pelajaran_id . ")\n";
        }
    }
}
