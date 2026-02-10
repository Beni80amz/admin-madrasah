<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Teacher::all() as $t) {
    echo "ID: " . $t->id . " | Name: " . $t->nama_lengkap . "\n";
}
