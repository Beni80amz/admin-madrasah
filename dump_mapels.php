<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mapels = App\Models\MataPelajaran::all(['id', 'nama', 'kode']);
file_put_contents('mapel_dump.json', json_encode($mapels, JSON_PRETTY_PRINT));
echo "Dumped " . count($mapels) . " mapels.\n";
