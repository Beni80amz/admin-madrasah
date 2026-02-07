<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

try {
    echo "Testing FileUpload methods...\n";
    $f = FileUpload::make('photo')
        ->label('Foto Profil')
        ->image()
        ->avatar()
        ->imageEditor();

    // Testing circle() vs circleCropper()
    if (method_exists($f, 'circle')) {
        echo "circle() EXISTS\n";
    } elseif (method_exists($f, 'circleCropper')) {
        echo "circle() MISSING, circleCropper() EXISTS\n";
    } else {
        echo "BOTH MISSING!\n";
    }

    echo "Testing Select methods...\n";
    $s = Select::make('test')
        ->relationship('jabatan', 'nama')
        ->searchable()
        ->preload()
        ->required();
    echo "Select SUCCESS\n";

} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
