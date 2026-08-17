<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Override APP_URL to match test server for signed URLs
config(['app.url' => 'http://127.0.0.1:8000']);
Illuminate\Support\Facades\URL::forceRootUrl('http://127.0.0.1:8000');

use App\Models\CommercialRequest;
use App\Models\CustomerType;
use App\Models\DeliveryMethod;
use App\Models\PaymentMethod;
use App\Models\State;
use Illuminate\Support\Facades\Http;

$base = 'http://127.0.0.1:8000';
$cookieFile = sys_get_temp_dir() . '/helin_pdf_test.txt';

function curlGet($url, $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $ct = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return ['status' => $status, 'body' => $body, 'content_type' => $ct];
}

function getCsrf($body) {
    preg_match('/<meta name="csrf-token" content="(.*?)">/', $body, $m);
    return $m[1] ?? null;
}

function curlJson($url, $post, $cookieFile, $csrf, $referer) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
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

// Create a real request with a real product in cart
$product = App\Models\Product::with('brand', 'category')->first();
if (!$product) {
    echo "No products found!" . PHP_EOL;
    exit;
}
echo "Using product: {$product->name} (slug={$product->slug}, price={$product->price})" . PHP_EOL;

@unlink($cookieFile);
$r = curlGet($base . '/solicitud', $cookieFile);
$csrf = getCsrf($r['body']);

$customerType = CustomerType::where('slug', 'doctor')->first();
$deliveryMethod = DeliveryMethod::where('slug', 'pickup')->first();
$paymentMethod = PaymentMethod::where('requires_receipt', false)->first();
$state = State::where('code', 'DC')->first();
$city = $state->cities()->first();

$r = curlJson($base . '/solicitud/send', [
    'tipo_cliente' => $customerType->slug,
    'nombre' => 'PDF',
    'apellido' => 'Test',
    'cedula' => 'V12345678',
    'telefono' => '1234567890',
    'email' => 'pdf-test@test.com',
    'estado' => $state->code,
    'ciudad' => $city->slug,
    'direccion' => 'Test address 123',
    'envio' => $deliveryMethod->slug,
    'pago' => $paymentMethod->name,
    'privacy_accepted' => 'on',
    'cart_items' => json_encode([[
        'id' => $product->slug,
        'name' => $product->name,
        'quantity' => 2,
        'price' => $product->price,
        'sku' => $product->sku ?? 'TEST-SKU',
        'dimension' => '10x20',
    ]]),
], $cookieFile, $csrf, $base . '/solicitud');

echo "Create request: {$r['status']}" . PHP_EOL;
$data = json_decode($r['body'], true);
if (!($data['success'] ?? false)) {
    echo "FAILED: " . json_encode($data) . PHP_EOL;
    exit;
}

$cr = CommercialRequest::where('email', 'pdf-test@test.com')->latest()->first();
echo "Request created: correlative={$cr->correlative}, uuid={$cr->uuid}" . PHP_EOL;

// Test PDF with signed URL
$signedUrl = Illuminate\Support\Facades\URL::signedRoute('pdf.cotizacion', ['uuid' => $cr->uuid]);
echo PHP_EOL . "=== PDF con URL firmada ===" . PHP_EOL;
echo "  URL: " . substr($signedUrl, 0, 80) . "..." . PHP_EOL;
$r = curlGet($signedUrl);
echo "  Status: {$r['status']}" . PHP_EOL;
echo "  Content-Type: {$r['content_type']}" . PHP_EOL;
echo "  Size: " . strlen($r['body']) . " bytes" . PHP_EOL;
$isPdf = str_contains($r['body'], '%PDF');
echo "  Is PDF: " . ($isPdf ? '✅ Yes' : '❌ No') . PHP_EOL;

// Test PDF without signed URL (should 403)
echo PHP_EOL . "=== PDF sin firma (debe dar 403) ===" . PHP_EOL;
$r = curlGet($base . '/pdf/cotizacion/' . $cr->uuid);
echo "  Status: {$r['status']} " . ($r['status'] == 403 ? '✅' : '❌') . PHP_EOL;

// Test solicitud-enviada page
echo PHP_EOL . "=== Página solicitud-enviada ===" . PHP_EOL;
$r = curlGet($base . '/solicitud-enviada/' . $cr->uuid);
echo "  Status: {$r['status']}" . PHP_EOL;
$hasCorrelative = str_contains($r['body'], $cr->correlative);
$hasPdfLink = str_contains($r['body'], 'pdf/cotizacion');
$hasWhatsapp = str_contains($r['body'], 'wa.me') || str_contains($r['body'], 'whatsapp');
echo "  Contains correlative: " . ($hasCorrelative ? '✅' : '❌') . PHP_EOL;
echo "  Contains PDF link: " . ($hasPdfLink ? '✅' : '❌') . PHP_EOL;
echo "  Contains WhatsApp link: " . ($hasWhatsapp ? '✅' : '❌') . PHP_EOL;

// Extract PDF content to verify fields
if ($isPdf) {
    file_put_contents(sys_get_temp_dir() . '/test_cotizacion.pdf', $r['body']);
    echo PHP_EOL . "  PDF saved to temp for inspection" . PHP_EOL;
}

// Cleanup
$cr->delete();
echo PHP_EOL . "✅ Request deleted" . PHP_EOL;
@unlink($cookieFile);
