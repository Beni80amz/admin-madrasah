<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mapels = App\Models\MataPelajaran::where('nama', 'like', '%Sejarah%')
    ->orWhere('nama', 'like', '%S K I%')
    ->get();

echo "MAPELS FOUND:\n";
foreach ($mapels as $m)
    echo "- ID: " . $m->id . " | Name: " . $m->nama . "\n";

$schedules = App\Models\JadwalPelajaran::with(['teacher', 'mataPelajaran'])
    ->whereIn('mata_pelajaran_id', $mapels->pluck('id'))
    ->get();

echo "\nSCHEDULES FOR SKI:\n";
foreach ($schedules as $s) {
    echo "- ID: " . $s->id . " | Teacher: " . ($s->teacher?->nama_lengkap ?? 'NULL') . " (ID: " . ($s->teacher_id ?? 'NULL') . ") | Mapel: " . ($s->mataPelajaran?->nama ?? 'NULL') . "\n";
}
