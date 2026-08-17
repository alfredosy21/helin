<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$c = App\Models\City::where('name','Caracas')->first();
echo 'id=' . $c->id . ' name=' . $c->name . ' slug=[' . $c->slug . '] state_id=' . $c->state_id . PHP_EOL;
echo 'cities with empty/null slug: ' . App\Models\City::whereNull('slug')->orWhere('slug','')->count() . PHP_EOL;
echo 'cities total: ' . App\Models\City::count() . PHP_EOL;
$empty = App\Models\City::whereNull('slug')->orWhere('slug','')->get();
foreach ($empty->take(10) as $e) {
    echo "  empty slug: id={$e->id} name={$e->name} state_id={$e->state_id}" . PHP_EOL;
}
