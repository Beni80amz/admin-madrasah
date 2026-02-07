<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Filament\Pages\MyProfile;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

try {
    echo "Creating MyProfile instance...\n";
    $page = new MyProfile();

    // Fake a user for Auth::user()
    $user = \App\Models\User::first();
    Auth::login($user);

    echo "Testing getSchema('form')...\n";
    $schema = $page->getSchema('form');

    if ($schema instanceof Schema) {
        echo "getSchema('form') SUCCESS\n";
        echo "Components count: " . count($schema->getComponents()) . "\n";
    } else {
        echo "getSchema('form') FAILED (not a Schema object)\n";
    }

} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "at " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
