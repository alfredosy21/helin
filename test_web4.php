<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$base = 'http://127.0.0.1:8000';
$cookieFile = sys_get_temp_dir() . '/helin_test2.txt';

function curlJson($url, $post, $cookieFile, $csrf, $referer) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'X-CSRF-TOKEN: ' . $csrf,
        'Referer: ' . $referer,
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'body' => $body];
}

function curlGet($url, $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'body' => $body];
}

function getCsrf($body) {
    preg_match('/<meta name="csrf-token" content="(.*?)">/', $body, $m);
    return $m[1] ?? null;
}

echo "=== TEST CONTACTO (AJAX JSON) ===" . PHP_EOL;
@unlink($cookieFile);
$r = curlGet($base . '/contactanos', $cookieFile);
$csrf = getCsrf($r['body']);

// Submit válido con JSON
$r = curlJson($base . '/contactanos/send', [
    'nombre' => 'Test User',
    'email' => 'test@test.com',
    'telefono' => '1234567890',
    'asunto' => 'Consulta de prueba',
    'mensaje' => 'Este es un mensaje de prueba automatizado para testing.',
], $cookieFile, $csrf, $base . '/contactanos');
echo "  Submit válido -> {$r['status']}" . PHP_EOL;
echo "  Response: " . substr($r['body'], 0, 200) . PHP_EOL;

if ($r['status'] == 200) {
    $cm = App\Models\ContactMessage::where('email', 'test@test.com')->latest()->first();
    if ($cm) {
        echo "  ✅ Guardado en BD: id=$cm->id" . PHP_EOL;
        $cm->delete();
        echo "  ✅ Eliminado" . PHP_EOL;
    }
}

// Submit inválido
$r = curlJson($base . '/contactanos/send', [
    'nombre' => '',
    'email' => 'invalid',
    'mensaje' => 'short',
], $cookieFile, $csrf, $base . '/contactanos');
echo "  Submit inválido -> {$r['status']}" . PHP_EOL;
$data = json_decode($r['body'], true);
if (isset($data['errors'])) {
    echo "  ✅ Errores de validación: " . implode(', ', array_keys($data['errors'])) . PHP_EOL;
}

echo PHP_EOL . "=== TEST SOLICITUD (AJAX JSON) ===" . PHP_EOL;
@unlink($cookieFile);
$r = curlGet($base . '/solicitud', $cookieFile);
$csrf = getCsrf($r['body']);

// Submit vacío
$r = curlJson($base . '/solicitud/send', [], $cookieFile, $csrf, $base . '/solicitud');
echo "  Submit vacío -> {$r['status']}" . PHP_EOL;
echo "  Response: " . substr($r['body'], 0, 300) . PHP_EOL;

// Submit completo
$customerType = App\Models\CustomerType::where('slug', 'doctor')->first();
$deliveryMethod = App\Models\DeliveryMethod::where('slug', 'pickup')->first();
$paymentMethod = App\Models\PaymentMethod::where('requires_receipt', false)->first();
$state = App\Models\State::first();
$city = $state ? $state->cities()->first() : null;

if ($customerType && $deliveryMethod && $paymentMethod && $state && $city) {
    $r = curlJson($base . '/solicitud/send', [
        'tipo_cliente' => $customerType->id,
        'nombre' => 'Test',
        'apellido' => 'User',
        'cedula' => 'V12345678',
        'telefono' => '1234567890',
        'email' => 'test@test.com',
        'estado' => $state->id,
        'ciudad' => $city->id,
        'direccion' => 'Test address 123',
        'observaciones' => 'Test observación',
        'envio' => $deliveryMethod->slug,
        'pago' => $paymentMethod->id,
        'privacy_accepted' => 'on',
        'cart_data' => json_encode([]),
    ], $cookieFile, $csrf, $base . '/solicitud');
    echo "  Submit completo -> {$r['status']}" . PHP_EOL;
    echo "  Response: " . substr($r['body'], 0, 300) . PHP_EOL;

    $cr = App\Models\CommercialRequest::where('email', 'test@test.com')->latest()->first();
    if ($cr) {
        echo "  ✅ Solicitud guardada: $cr->correlative" . PHP_EOL;
        // PDF
        $r2 = curlGet($base . '/pdf/cotizacion/' . $cr->uuid);
        echo "  PDF -> {$r2['status']} (" . strlen($r2['body']) . " bytes)" . PHP_EOL;
        // Solicitud enviada
        $r3 = curlGet($base . '/solicitud-enviada/' . $cr->uuid);
        echo "  Solicitud enviada -> {$r3['status']}" . PHP_EOL;
        $cr->delete();
        echo "  ✅ Eliminada" . PHP_EOL;
    } else {
        echo "  ❌ NO guardada" . PHP_EOL;
    }
}

echo PHP_EOL . "=== INVESTIGAR MENÚ HEADER (nombres vacíos) ===" . PHP_EOL;
$menus = App\Models\Menus::where('type', 'header')->orderBy('position')->get();
foreach ($menus as $m) {
    echo sprintf("  id=%s name='%s' url='%s' type=%s parent=%s pos=%s active=%s",
        $m->id, $m->name, $m->url, $m->type, $m->parent_id, $m->position, $m->is_active) . PHP_EOL;
}

echo PHP_EOL . "=== INVESTIGAR SOLICITUD ROUTE ===" . PHP_EOL;
// Verificar qué controlador maneja /solicitud/send
$route = app('router')->getRoutes()->get('POST');
foreach ($route as $r) {
    if (str_contains($r->uri(), 'solicitud')) {
        echo "  POST {$r->uri()} -> " . get_class($r->getController()) . "::" . $r->getActionMethod() . PHP_EOL;
    }
}

@unlink($cookieFile);
