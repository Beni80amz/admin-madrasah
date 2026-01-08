<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\Teacher;

$student = Student::first();
if ($student && !$student->user_id) {
    echo "Linking User for Student...\n";
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'siswa@madrasah.com'], // Dummy email
        ['name' => $student->nama_lengkap, 'password' => bcrypt('password')]
    );
    $student->user_id = $user->id;
    $student->save();
}

$teacher = Teacher::first();
if ($teacher && !$teacher->user_id) {
    echo "Linking User for Teacher...\n";
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'guru@madrasah.com'], // Dummy email
        ['name' => $teacher->nama_lengkap, 'password' => bcrypt('password')]
    );
    $teacher->user_id = $user->id;
    $teacher->save();
}

// Refresh
$student = Student::whereNotNull('user_id')->first();
$teacher = Teacher::whereNotNull('user_id')->first();

echo "STUDENT_NIS:" . ($student ? $student->nis_lokal : 'None') . PHP_EOL;
echo "TEACHER_NIP:" . ($teacher ? $teacher->nip : 'None') . PHP_EOL;
