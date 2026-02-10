<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Search teachers with SKI or Bahasa Arab in their name or mapel
$teachers = App\Models\Teacher::with('mataPelajaran')
    ->get()
    ->filter(function ($t) {
        $name = strtolower($t->nama_lengkap);
        return str_contains($name, 'beni') || str_contains($name, 'solehudin');
    });

if ($teachers->isEmpty()) {
    echo "No teachers found with 'Beni' or 'Solehudin' in name.\n";
    // Try to find by mapel
    $mapelIds = App\Models\MataPelajaran::where('nama', 'S K I')
        ->orWhere('nama', 'Sejarah Kebudayaan Islam')
        ->pluck('id');

    $teachersByMapel = App\Models\Teacher::whereIn('mata_pelajaran_id', $mapelIds)->get();
    echo "Found " . $teachersByMapel->count() . " teachers by SKI mapel.\n";
    foreach ($teachersByMapel as $t) {
        echo "- " . $t->nama_lengkap . " (ID: " . $t->id . ")\n";
    }
} else {
    foreach ($teachers as $t) {
        echo "Teacher: " . $t->nama_lengkap . " (ID: " . $t->id . ")\n";
        echo "Mapel Utama: " . ($t->mataPelajaran?->nama ?? 'NULL') . "\n";

        // Check schedule
        $schedules = App\Models\JadwalPelajaran::with('mataPelajaran')
            ->where('teacher_id', $t->id)
            ->get();

        echo "Total Schedules: " . $schedules->count() . "\n";
        foreach ($schedules->groupBy('mata_pelajaran_id') as $mapelId => $group) {
            $mapelName = $group->first()->mataPelajaran?->nama ?? 'Unknown';
            echo "  - Mapel: $mapelName (ID: $mapelId) | Count: " . $group->count() . "\n";
        }
    }
}
