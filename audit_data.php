<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Teachers LIST ---\n";
$teachers = App\Models\Teacher::all();
foreach ($teachers as $t) {
    $count = App\Models\JadwalPelajaran::where('teacher_id', $t->id)->count();
    echo "ID: {$t->id} | Name: {$t->nama_lengkap} | Mapel ID: " . ($t->mata_pelajaran_id ?? 'NULL') . " | Schedules Count: {$count}\n";
}

echo "\n--- Recent Schedules ---\n";
$schedules = App\Models\JadwalPelajaran::with(['teacher', 'mataPelajaran'])->latest()->take(20)->get();
foreach ($schedules as $s) {
    echo "ID: {$s->id} | Teacher: " . ($s->teacher?->nama_lengkap ?? 'UNKNOWN') . " | Mapel: " . ($s->mataPelajaran?->nama ?? 'UNKNOWN') . " | TA: {$s->tahun_ajaran_id} | Sem: {$s->semester}\n";
}
