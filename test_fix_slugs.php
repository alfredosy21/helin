<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Str;

$updated = 0;
App\Models\City::whereNull('slug')->orWhere('slug','')->chunkById(200, function ($cities) use (&$updated) {
    foreach ($cities as $city) {
        $city->slug = Str::slug($city->name);
        $city->save();
        $updated++;
    }
});
echo "Updated $updated city slugs" . PHP_EOL;

// Verify
$empty = App\Models\City::whereNull('slug')->orWhere('slug','')->count();
echo "Cities with empty slug now: $empty" . PHP_EOL;

$state = App\Models\State::where('code','DC')->first();
$city = $state->cities()->first();
echo "Sample: state={$state->code} city={$city->name} slug={$city->slug}" . PHP_EOL;
