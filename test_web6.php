<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$base = 'http://127.0.0.1:8000';
$cookieFile = sys_get_temp_dir() . '/helin_test6.txt';

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

echo "reCAPTCHA enabled: " . var_export(config('services.recaptcha.enabled'), true) . PHP_EOL . PHP_EOL;

echo "=== TEST CONTACTO (AJAX JSON, sin reCAPTCHA) ===" . PHP_EOL;
@unlink($cookieFile);
$r = curlGet($base . '/contactanos', $cookieFile);
$csrf = getCsrf($r['body']);

$r = curlJson($base . '/contactanos/send', [
    'nombre' => 'Test User',
    'email' => 'test@test.com',
    'telefono' => '1234567890',
    'asunto' => 'Consulta de prueba',
    'mensaje' => 'Este es un mensaje de prueba automatizado para testing.',
], $cookieFile, $csrf, $base . '/contactanos');
echo "  Submit válido -> {$r['status']}" . PHP_EOL;
$data = json_decode($r['body'], true);
if ($data === null) {
    echo "  Response (raw): " . substr($r['body'], 0, 200) . PHP_EOL;
} else {
    echo "  Response: " . ($data['success'] ? '✅ success' : '❌ ' . ($data['message'] ?? json_encode($data['errors'] ?? []))) . PHP_EOL;
}

if ($r['status'] == 200 && ($data['success'] ?? false)) {
    $cm = App\Models\ContactMessage::where('email', 'test@test.com')->latest()->first();
    if ($cm) {
        echo "  ✅ Guardado en BD: id=$cm->id, nombre=$cm->nombre" . PHP_EOL;
        $cm->delete();
        echo "  ✅ Eliminado" . PHP_EOL;
    } else {
        echo "  ❌ NO guardado en BD (pero response fue 200)" . PHP_EOL;
    }
}

echo PHP_EOL . "=== TEST SOLICITUD (AJAX JSON, valores correctos) ===" . PHP_EOL;
@unlink($cookieFile);
$r = curlGet($base . '/solicitud', $cookieFile);
$csrf = getCsrf($r['body']);

// Submit vacío
$r = curlJson($base . '/solicitud/send', [], $cookieFile, $csrf, $base . '/solicitud');
echo "  Submit vacío -> {$r['status']} (debe ser 422)" . PHP_EOL;

// Submit completo con valores correctos (slugs/codes/names)
$customerType = App\Models\CustomerType::where('slug', 'doctor')->first();
$deliveryMethod = App\Models\DeliveryMethod::where('slug', 'pickup')->first();
$paymentMethod = App\Models\PaymentMethod::where('requires_receipt', false)->first();
$state = App\Models\State::first();
$city = $state ? $state->cities()->first() : null;

echo "  Valores: ct={$customerType->slug}, state={$state->code}, city={$city->slug}, dm={$deliveryMethod->slug}, pm={$paymentMethod->name}" . PHP_EOL;

$r = curlJson($base . '/solicitud/send', [
    'tipo_cliente' => $customerType->slug,
    'nombre' => 'Test',
    'apellido' => 'User',
    'cedula' => 'V12345678',
    'telefono' => '1234567890',
    'email' => 'test@test.com',
    'estado' => $state->code,
    'ciudad' => $city->slug,
    'direccion' => 'Test address 123',
    'observaciones' => 'Test observación',
    'envio' => $deliveryMethod->slug,
    'pago' => $paymentMethod->name,
    'privacy_accepted' => 'on',
    'cart_items' => json_encode([[
        'id' => 'test-product-slug',
        'name' => 'Test Product',
        'quantity' => 2,
        'price' => 100.50,
        'sku' => 'TEST-001',
        'dimension' => '10x20',
    ]]),
], $cookieFile, $csrf, $base . '/solicitud');
echo "  Submit completo -> {$r['status']}" . PHP_EOL;
$data = json_decode($r['body'], true);
if ($data['success'] ?? false) {
    echo "  ✅ Response success" . PHP_EOL;
    $cr = App\Models\CommercialRequest::where('email', 'test@test.com')->latest()->first();
    if ($cr) {
        echo "  ✅ Solicitud guardada: correlative=$cr->correlative, status=$cr->status" . PHP_EOL;
        // PDF
        $r2 = curlGet($base . '/pdf/cotizacion/' . $cr->uuid);
        $isPdf = str_contains($r2['body'], '%PDF') || str_contains($r2['body'], '<!DOCTYPE html>');
        echo "  PDF -> {$r2['status']} (" . strlen($r2['body']) . " bytes, " . ($isPdf ? 'contenido válido' : 'vacío') . ")" . PHP_EOL;
        // Solicitud enviada
        $r3 = curlGet($base . '/solicitud-enviada/' . $cr->uuid);
        echo "  Solicitud enviada -> {$r3['status']}" . PHP_EOL;
        $cr->delete();
        echo "  ✅ Eliminada" . PHP_EOL;
    } else {
        echo "  ❌ NO guardada en BD" . PHP_EOL;
    }
} else {
    echo "  ❌ Response: " . json_encode($data['errors'] ?? $data) . PHP_EOL;
}

echo PHP_EOL . "=== TEST SOLICITUD CON ENVÍO (no pickup) ===" . PHP_EOL;
$deliveryMethod2 = App\Models\DeliveryMethod::where('slug', 'mrw')->first();
if ($deliveryMethod2) {
    @unlink($cookieFile);
    $r = curlGet($base . '/solicitud', $cookieFile);
    $csrf = getCsrf($r['body']);
    $r = curlJson($base . '/solicitud/send', [
        'tipo_cliente' => $customerType->slug,
        'nombre' => 'Test',
        'apellido' => 'User',
        'telefono' => '1234567890',
        'email' => 'test2@test.com',
        'estado' => $state->code,
        'ciudad' => $city->slug,
        'direccion' => 'Test address 123',
        'envio' => $deliveryMethod2->slug,
        'destinatario_nombre' => 'Dest Test',
        'destinatario_documento' => 'V87654321',
        'destinatario_telefono' => '0987654321',
        'envio_estado' => $state->code,
        'envio_ciudad' => $city->slug,
        'agencia_destino' => 'Agencia Test',
        'pago' => $paymentMethod->name,
        'privacy_accepted' => 'on',
        'cart_items' => json_encode([[
            'id' => 'test-product-slug',
            'name' => 'Test Product',
            'quantity' => 1,
            'price' => 50.00,
            'sku' => 'TEST-002',
            'dimension' => '5x10',
        ]]),
    ], $cookieFile, $csrf, $base . '/solicitud');
    echo "  Submit con envío MRW -> {$r['status']}" . PHP_EOL;
    $data = json_decode($r['body'], true);
    if ($data['success'] ?? false) {
        echo "  ✅ Response success" . PHP_EOL;
        $cr = App\Models\CommercialRequest::where('email', 'test2@test.com')->latest()->first();
        if ($cr) {
            echo "  ✅ Solicitud guardada: correlative=$cr->correlative" . PHP_EOL;
            echo "  recipient_name=$cr->recipient_name, shippingState=" . ($cr->shippingState?->name ?? 'NULL') . PHP_EOL;
            $cr->delete();
            echo "  ✅ Eliminada" . PHP_EOL;
        }
    } else {
        echo "  ❌ " . json_encode($data['errors'] ?? $data) . PHP_EOL;
    }
}

echo PHP_EOL . "=== TEST SOLICITUD CON EMPRESA ===" . PHP_EOL;
$ctEmpresa = App\Models\CustomerType::where('slug', 'empresa')->first();
if ($ctEmpresa) {
    @unlink($cookieFile);
    $r = curlGet($base . '/solicitud', $cookieFile);
    $csrf = getCsrf($r['body']);
    $r = curlJson($base . '/solicitud/send', [
        'tipo_cliente' => $ctEmpresa->slug,
        'nombre' => 'Test',
        'apellido' => 'User',
        'empresa' => 'Test Company C.A.',
        'rif' => 'J123456789',
        'telefono' => '1234567890',
        'email' => 'test3@test.com',
        'estado' => $state->code,
        'ciudad' => $city->slug,
        'direccion' => 'Test address 123',
        'envio' => $deliveryMethod->slug,
        'pago' => $paymentMethod->name,
        'privacy_accepted' => 'on',
        'cart_items' => json_encode([[
            'id' => 'test-product-slug',
            'name' => 'Test Product',
            'quantity' => 3,
            'price' => 75.00,
            'sku' => 'TEST-003',
            'dimension' => '15x25',
        ]]),
    ], $cookieFile, $csrf, $base . '/solicitud');
    echo "  Submit empresa -> {$r['status']}" . PHP_EOL;
    $data = json_decode($r['body'], true);
    if ($data['success'] ?? false) {
        echo "  ✅ Response success" . PHP_EOL;
        $cr = App\Models\CommercialRequest::where('email', 'test3@test.com')->latest()->first();
        if ($cr) {
            echo "  ✅ Solicitud guardada: company=$cr->company_name, rif=$cr->rif" . PHP_EOL;
            $cr->delete();
            echo "  ✅ Eliminada" . PHP_EOL;
        }
    } else {
        echo "  ❌ " . json_encode($data['errors'] ?? $data) . PHP_EOL;
    }
}

@unlink($cookieFile);
