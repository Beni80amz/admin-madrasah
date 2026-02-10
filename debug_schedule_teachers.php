<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teacherIds = App\Models\JadwalPelajaran::distinct()->pluck('teacher_id');
foreach ($teacherIds as $id) {
    if (!$id)
        continue;
    $teacher = App\Models\Teacher::find($id);
    echo "ID: " . $id . " | Name in DB: " . ($teacher->nama_lengkap ?? 'UNKNOWN') . "\n";
}
