<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rombelId = 3; // 6-C from image
$schedules = App\Models\JadwalPelajaran::with(['mataPelajaran', 'teacher'])
    ->where('rombel_id', $rombelId)
    ->get();

echo "SCHEDULES FOR ROMBEL ID $rombelId\n";
echo str_repeat("-", 80) . "\n";

foreach ($schedules as $s) {
    echo sprintf(
        "ID: %-4d | Mapel: %-30s (ID: %-3d) | Teacher: %-30s (ID: %-3d)\n",
        $s->id,
        $s->mataPelajaran?->nama ?? 'NULL',
        $s->mata_pelajaran_id ?? 0,
        $s->teacher?->nama_lengkap ?? 'NULL',
        $s->teacher_id ?? 0
    );
}
