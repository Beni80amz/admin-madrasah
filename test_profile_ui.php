<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Filament\Pages\MyProfile;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Actions;

echo "Creating MyProfile instance...\n";
$page = new MyProfile();

echo "Getting form schema...\n";
$schema = $page->getSchema('form');

$components = $schema->getComponents();
echo "Root Components count: " . count($components) . "\n";

foreach ($components as $component) {
    if ($component instanceof Grid) {
        echo "Found Grid component.\n";
        $gridSchema = $component->getChildSchema();
        $gridComponents = $gridSchema->getComponents();
        echo "  Grid children: " . count($gridComponents) . "\n";

        foreach ($gridComponents as $index => $gridComponent) {
            echo "  Child $index is " . get_class($gridComponent) . "\n";
            if ($gridComponent instanceof Section) {
                $sectionSchema = $gridComponent->getChildSchema();
                $sectionComponents = $sectionSchema->getComponents();
                echo "    Section $index has " . count($sectionComponents) . " components.\n";

                foreach ($sectionComponents as $secComp) {
                    echo "      - " . get_class($secComp) . "\n";
                    if ($secComp instanceof Actions) {
                        echo "      *** FOUND ACTIONS COMPONENT ***\n";
                    }
                }
            }
        }
    }
}
