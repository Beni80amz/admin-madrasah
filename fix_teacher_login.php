<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;
use App\Models\User;

$nip = '3201012908800004';
$teacher = Teacher::where('nip', $nip)->first();

if ($teacher) {
    echo "Found Teacher: " . $teacher->nama_lengkap . "\n";
    if (!$teacher->user_id) {
        echo "Creating User...\n";
        $user = User::create([
            'name' => $teacher->nama_lengkap,
            'email' => 'nip' . $nip . '@madrasah.com',
            'password' => bcrypt('password')
        ]);
        $teacher->user_id = $user->id;
        $teacher->save();
        echo "User Created and Linked.\n";
    } else {
        echo "User already linked. Resetting password...\n";
        $user = User::find($teacher->user_id);
        if ($user) {
            $user->password = bcrypt('password');
            $user->save();
            echo "Password matched to 'password'.\n";
        } else {
            // Orphaned ID
            echo "User ID exists but User not found. Recreating...\n";
            $user = User::create([
                'name' => $teacher->nama_lengkap,
                'email' => 'nip' . $nip . '@madrasah.com',
                'password' => bcrypt('password')
            ]);
            $teacher->user_id = $user->id;
            $teacher->save();
        }
    }
} else {
    echo "Teacher with NIP $nip NOT FOUND.\n";
}
