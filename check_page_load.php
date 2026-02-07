<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (class_exists('App\Filament\Pages\MyAttendance')) {
    echo "Class App\Filament\Pages\MyAttendance EXISTS.\n";
    $page = new App\Filament\Pages\MyAttendance();
    echo "Instance created successfully.\n";
    echo "Navigation Group: " . App\Filament\Pages\MyAttendance::getNavigationGroup() . "\n";
    echo "Should Register Navigation: " . (App\Filament\Pages\MyAttendance::shouldRegisterNavigation() ? 'YES' : 'NO') . "\n";
} else {
    echo "Class App\Filament\Pages\MyAttendance NOT FOUND.\n";
}
