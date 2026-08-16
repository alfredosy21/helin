<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$s = App\Models\Settings::getSettings();
echo 'offices: ' . json_encode($s ? $s->offices : null) . PHP_EOL;
echo 'valencia_whatsapp: ' . ($s->valencia_whatsapp ?? 'NULL') . PHP_EOL;
