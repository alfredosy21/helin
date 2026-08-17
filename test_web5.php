<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== MENÚ: todos los registros ===" . PHP_EOL;
$menus = App\Models\Menus::all();
echo "Total: " . $menus->count() . PHP_EOL;
foreach ($menus as $m) {
    echo sprintf("  id=%s name='%s' url='%s' type='%s' parent=%s pos=%s active=%s",
        $m->id, $m->name ?? 'NULL', $m->url ?? 'NULL', $m->type ?? 'NULL',
        $m->parent_id ?? 'NULL', $m->position ?? 'NULL', $m->is_active ?? 'NULL') . PHP_EOL;
}

echo PHP_EOL . "=== MENÚ: tipos disponibles ===" . PHP_EOL;
$types = App\Models\Menus::select('type')->distinct()->pluck('type');
foreach ($types as $t) {
    $count = App\Models\Menus::where('type', $t)->count();
    echo "  type='$t' count=$count" . PHP_EOL;
}

echo PHP_EOL . "=== MENÚ: getHeaderItems() ===" . PHP_EOL;
$items = App\Models\Menus::getHeaderItems();
echo "Items: " . $items->count() . PHP_EOL;
foreach ($items as $item) {
    echo sprintf("  name='%s' url='%s' pos=%s", $item->name ?? 'NULL', $item->url ?? 'NULL', $item->position ?? 'NULL') . PHP_EOL;
}

echo PHP_EOL . "=== SOLICITUD: validación ===" . PHP_EOL;
$customerType = App\Models\CustomerType::where('slug', 'doctor')->first();
echo "CustomerType doctor: id=" . $customerType->id . " slug=" . $customerType->slug . PHP_EOL;

$state = App\Models\State::first();
echo "State: id=" . $state->id . " name=" . $state->name . PHP_EOL;
$city = $state->cities()->first();
echo "City: id=" . ($city ? $city->id : 'NULL') . " name=" . ($city ? $city->name : 'NULL') . PHP_EOL;

$paymentMethod = App\Models\PaymentMethod::where('requires_receipt', false)->first();
echo "PaymentMethod: id=" . $paymentMethod->id . " name=" . $paymentMethod->name . PHP_EOL;

echo PHP_EOL . "=== SOLICITUD: reglas de validación ===" . PHP_EOL;
$controller = new App\Http\Controllers\Web\CommercialRequestController();
$reflection = new ReflectionClass($controller);
if ($reflection->hasMethod('store')) {
    $method = $reflection->getMethod('store');
    echo "Method store found" . PHP_EOL;
    // Try to get the rules from the method
    $params = $method->getParameters();
    foreach ($params as $p) {
        echo "  Param: " . $p->getName() . " type=" . ($p->getType() ?: 'mixed') . PHP_EOL;
    }
}

echo PHP_EOL . "=== RECAPTCHA config ===" . PHP_EOL;
echo "enabled: " . var_export(config('services.recaptcha.enabled'), true) . PHP_EOL;
echo "site_key: " . (config('services.recaptcha.site_key') ? 'SET' : 'NOT SET') . PHP_EOL;
echo "secret_key: " . (config('services.recaptcha.secret_key') ? 'SET' : 'NOT SET') . PHP_EOL;
