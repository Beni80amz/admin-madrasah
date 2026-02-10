<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teacherIds = App\Models\JadwalPelajaran::distinct()->pluck('teacher_id');
$nonKbmMapels = ['Istirahat', 'Soliskan', 'Shalat Dluha', '7 Kebiasaan Anak Indonesia Hebat'];
$agamaMapels = ['Al Quran Hadits', 'Akidah Akhlak', 'Fikih', 'S K I', 'Sejarah Kebudayaan Islam'];

echo "TEACHERS WITH SCHEDULES\n";
echo str_repeat("-", 80) . "\n";

foreach ($teacherIds as $id) {
    if (!$id)
        continue;
    $teacher = App\Models\Teacher::with(['mataPelajaran', 'jabatan'])->find($id);
    if (!$teacher) {
        echo "Teacher ID $id NOT FOUND in teachers table.\n";
        continue;
    }

    $allSchedules = App\Models\JadwalPelajaran::with('mataPelajaran')
        ->where('teacher_id', $id)
        ->get()
        ->filter(function ($s) use ($nonKbmMapels) {
            return !in_array($s->mataPelajaran?->nama, $nonKbmMapels);
        });

    $reg = $allSchedules->count();

    // Simulate Linier
    $isWali = \App\Models\Rombel::where('wali_kelas_id', $id)->exists();
    $isGuruKelas = $teacher->jabatan?->nama === 'Guru Kelas' || $isWali;

    $linier = 0;
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
        $linier = $allSchedules->filter(function ($s) use ($linierMapels) {
            return in_array($s->mataPelajaran?->nama, $linierMapels);
        })->count();
    } else {
        $mainMapel = $teacher->mataPelajaran?->nama;
        if (in_array($mainMapel, $agamaMapels)) {
            $linier = $allSchedules->filter(function ($s) use ($agamaMapels) {
                return in_array($s->mataPelajaran?->nama, $agamaMapels);
            })->count();
        } else {
            $linier = $allSchedules->filter(function ($s) use ($teacher) {
                return $s->mata_pelajaran_id == $teacher->mata_pelajaran_id;
            })->count();
        }
    }

    echo sprintf(
        "ID: %-3d | Name: %-30s | Main: %-20s | Reg: %-2d | Linier: %-2d\n",
        $id,
        $teacher->nama_lengkap,
        $teacher->mataPelajaran?->nama ?? 'NULL',
        $reg,
        $linier
    );
}
