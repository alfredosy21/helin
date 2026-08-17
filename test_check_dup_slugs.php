<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$duplicates = App\Models\City::select('slug')
    ->groupBy('slug')
    ->havingRaw('COUNT(*) > 1')
    ->get();
echo "Duplicate slugs: " . $duplicates->count() . PHP_EOL;
foreach ($duplicates as $d) {
    $cities = App\Models\City::where('slug', $d->slug)->get();
    echo "  slug={$d->slug}:" . PHP_EOL;
    foreach ($cities as $c) {
        echo "    id={$c->id} name={$c->name} state=" . $c->state->code . PHP_EOL;
    }
}
