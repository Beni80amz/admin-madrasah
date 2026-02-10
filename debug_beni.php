<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teacher = App\Models\Teacher::with(['mataPelajaran', 'jabatan', 'tugasTambahan'])
    ->where('nama_lengkap', 'like', '%Beni Solehudin%')
    ->first();

if ($teacher) {
    echo "Teacher: " . $teacher->nama_lengkap . "\n";
    echo "Mapel Utama: " . ($teacher->mataPelajaran?->nama ?? 'NULL') . " (ID: " . ($teacher->mata_pelajaran_id ?? 'NULL') . ")\n";
    echo "Jabatan: " . ($teacher->jabatan?->nama ?? 'NULL') . "\n";
    echo "Tugas Tambahan: " . ($teacher->tugasTambahan?->nama ?? 'NULL') . "\n";

    $isWali = \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->exists();
    echo "Is Wali Kelas: " . ($isWali ? 'YES' : 'NO') . "\n";
} else {
    echo "Teacher not found.\n";
}
