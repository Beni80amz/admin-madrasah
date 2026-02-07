<?php

require __DIR__ . '/vendor/autoload.php';

$methods = ['getNavigationIcon', 'getNavigationLabel', 'getNavigationGroup', 'getTitle'];
$classes = [\Filament\Pages\Page::class, \Filament\Resources\Pages\BasePage::class];

foreach ($methods as $m) {
    foreach ($classes as $c) {
        if (method_exists($c, $m)) {
            $r = new ReflectionMethod($c, $m);
            echo $m . ': ' . (string) $r->getReturnType() . "\n";
            continue 2;
        }
    }
    echo $m . ": NOT FOUND\n";
}
