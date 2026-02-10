<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('name', 'like', '%Fahmi%')->with('teacher.jabatan')->first();
if ($user) {
    echo json_encode($user->toArray(), JSON_PRETTY_PRINT);
} else {
    echo "NOT FOUND";
}
