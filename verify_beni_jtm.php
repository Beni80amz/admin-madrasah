<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teacher = App\Models\Teacher::where('nama_lengkap', 'like', '%Beni%')->first();
$tahunAjaranId = 1; // Assuming Genap/TA 1
$semester = 'genap';

echo "TEACHER: {$teacher->nama_lengkap} (ID: {$teacher->id})\n";
echo "MAIN MAPEL: " . ($teacher->mataPelajaran?->nama ?? 'NULL') . "\n";

$agamaLinier = ['Al Quran Hadits', 'Akidah Akhlak', 'Fikih', 'S K I', 'Sejarah Kebudayaan Islam'];
$agamaGroup = array_merge($agamaLinier, ['Bahasa Arab', 'B. Arab']);

$allSchedules = App\Models\JadwalPelajaran::with('mataPelajaran')
    ->where('teacher_id', $teacher->id)
    ->where('tahun_ajaran_id', $tahunAjaranId)
    ->where('semester', $semester)
    ->get();

$jtmReguler = $allSchedules->count();
echo "JTM REGULER: {$jtmReguler}\n";

$teacherMainMapel = trim($teacher->mataPelajaran?->nama ?? '');
$isAgamaTeacher = in_array($teacherMainMapel, $agamaGroup);

if (!$isAgamaTeacher && !$teacher->mata_pelajaran_id) {
    echo "AUTO-DETECTING...\n";
    $teachesAgama = $allSchedules->contains(function ($s) use ($agamaLinier) {
        return in_array(trim($s->mataPelajaran?->nama), $agamaLinier);
    });
    if ($teachesAgama) {
        $isAgamaTeacher = true;
        echo "RESULT: IS AGAMA TEACHER (Auto-detected)\n";
    }
}

if ($isAgamaTeacher) {
    $jtmLinier = $allSchedules->filter(function ($s) use ($agamaLinier) {
        return in_array(trim($s->mataPelajaran?->nama), $agamaLinier);
    })->count();
    echo "JTM LINIER: {$jtmLinier}\n";
} else {
    echo "JTM LINIER: 0 (Not detected as Agama teacher)\n";
}
