<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teacher = App\Models\Teacher::where('nama_lengkap', 'like', '%Fahmi%')->with('jabatan', 'user')->first();
if ($teacher) {
    echo json_encode($teacher->toArray(), JSON_PRETTY_PRINT);
} else {
    echo "NOT FOUND";
}
