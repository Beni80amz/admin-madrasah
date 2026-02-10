<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- BENI SEARCH ---\n";
$teachers = App\Models\Teacher::where('nama_lengkap', 'like', '%Beni%')->get();
if ($teachers->isEmpty()) {
    echo "NO TEACHER FOUND WITH 'BENI'\n";
    // Search by Mapel SKI
    echo "SEARCHING BY SKI MAPEL...\n";
    $skiMapels = App\Models\MataPelajaran::where('nama', 'like', '%Sejarah%')->orWhere('nama', 'like', '%S K I%')->pluck('id');
    $teachersByMapel = App\Models\Teacher::whereIn('mata_pelajaran_id', $skiMapels)->get();
    foreach ($teachersByMapel as $t) {
        echo "ID: {$t->id} | Name: {$t->nama_lengkap} | Mapel: " . ($t->mataPelajaran?->nama) . "\n";
    }
} else {
    foreach ($teachers as $t) {
        echo "ID: {$t->id} | Name: {$t->nama_lengkap} | Mapel: " . ($t->mataPelajaran?->nama ?? 'NULL') . " | User: " . ($t->user?->name ?? 'NULL') . "\n";
    }
}

echo "\n--- IDENTIFYING RELIGIOUS TEACHERS ---\n";
$agamaGroup = ['Al Quran Hadits', 'Akidah Akhlak', 'Fikih', 'S K I', 'Sejarah Kebudayaan Islam', 'Bahasa Arab', 'B. Arab'];
$religiousTeachers = App\Models\Teacher::whereHas('mataPelajaran', function ($q) use ($agamaGroup) {
    $q->whereIn('nama', $agamaGroup);
})->get();
foreach ($religiousTeachers as $rt) {
    echo "ID: {$rt->id} | Name: {$rt->nama_lengkap} | Main Mapel: {$rt->mataPelajaran->nama}\n";
}
