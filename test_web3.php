<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$base = 'http://127.0.0.1:8000';
$cookieFile = sys_get_temp_dir() . '/helin_test_cookies.txt';

function curlRequest($url, $post = null, $cookieFile = null, $csrf = null, $referer = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        if ($csrf) $headers[] = 'X-CSRF-TOKEN: ' . $csrf;
        if ($referer) $headers[] = 'Referer: ' . $referer;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirect = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    curl_close($ch);
    return ['status' => $status, 'body' => $body, 'redirect' => $redirect];
}

function getCsrf($body) {
    preg_match('/<meta name="csrf-token" content="(.*?)">/', $body, $m);
    return $m[1] ?? null;
}

echo "=== TEST FORMULARIO CONTACTO (con CSRF + cookies) ===" . PHP_EOL;
@unlink($cookieFile);
$r = curlRequest($base . '/contactanos', null, $cookieFile);
$csrf = getCsrf($r['body']);

if ($csrf) {
    echo "  CSRF token: ✅" . PHP_EOL;

    // Submit válido
    $r = curlRequest($base . '/contactanos/send', [
        'nombre' => 'Test User',
        'email' => 'test@test.com',
        'telefono' => '1234567890',
        'asunto' => 'Consulta de prueba',
        'mensaje' => 'Este es un mensaje de prueba automatizado.',
        'privacy_accepted' => 'on',
    ], $cookieFile, $csrf, $base . '/contactanos');
    echo sprintf("  %s Submit válido -> %d (redirect=%s)", ($r['status'] >= 300 && $r['status'] < 400 ? '✅' : '❌'), $r['status'], $r['redirect'] ?? 'N/A') . PHP_EOL;

    // Verificar guardado
    $cm = App\Models\ContactMessage::where('email', 'test@test.com')->latest()->first();
    if ($cm) {
        echo sprintf("  ✅ Guardado en BD: id=%s, nombre=%s, asunto=%s", $cm->id, $cm->nombre, $cm->asunto) . PHP_EOL;
        $cm->delete();
        echo "  ✅ Mensaje de prueba eliminado" . PHP_EOL;
    } else {
        echo "  ❌ NO guardado en BD" . PHP_EOL;
    }

    // Submit inválido
    $r = curlRequest($base . '/contactanos/send', [
        'nombre' => '',
        'email' => 'invalid',
        'mensaje' => '',
    ], $cookieFile, $csrf, $base . '/contactanos');
    echo sprintf("  %s Submit inválido -> %d", ($r['status'] >= 300 && $r['status'] < 400 ? '✅' : '❌'), $r['status']) . PHP_EOL;
} else {
    echo "  ❌ No CSRF token" . PHP_EOL;
}

echo PHP_EOL . "=== TEST FORMULARIO SOLICITUD (con CSRF + cookies) ===" . PHP_EOL;
@unlink($cookieFile);
$r = curlRequest($base . '/solicitud', null, $cookieFile);
$csrf = getCsrf($r['body']);

if ($csrf) {
    // Submit vacío
    $r = curlRequest($base . '/solicitud/send', [], $cookieFile, $csrf, $base . '/solicitud');
    echo sprintf("  %s Submit vacío -> %d (debe redirigir con errores)", ($r['status'] >= 300 && $r['status'] < 500 ? '✅' : '❌'), $r['status']) . PHP_EOL;

    // Submit completo
    $customerType = App\Models\CustomerType::where('slug', 'doctor')->first();
    $deliveryMethod = App\Models\DeliveryMethod::where('slug', 'pickup')->first();
    $paymentMethod = App\Models\PaymentMethod::where('requires_receipt', false)->first();
    $state = App\Models\State::first();
    $city = $state ? $state->cities()->first() : null;

    if ($customerType && $deliveryMethod && $paymentMethod && $state && $city) {
        $r = curlRequest($base . '/solicitud/send', [
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
        echo sprintf("  %s Submit completo -> %d (redirect=%s)", ($r['status'] >= 300 && $r['status'] < 400 ? '✅' : '❌'), $r['status'], $r['redirect'] ?? 'N/A') . PHP_EOL;

        $cr = App\Models\CommercialRequest::where('email', 'test@test.com')->latest()->first();
        if ($cr) {
            echo sprintf("  ✅ Solicitud guardada: correlative=%s, status=%s, type=%s", $cr->correlative, $cr->status, $cr->customerType?->name) . PHP_EOL;

            // PDF
            $r2 = curlRequest($base . '/pdf/cotizacion/' . $cr->uuid);
            $isPdf = str_contains($r2['body'], '%PDF') || str_contains($r2['body'], '<!DOCTYPE html');
            echo sprintf("  %s PDF cotización -> %d (body: %d bytes)", ($r2['status'] < 400 ? '✅' : '❌'), $r2['status'], strlen($r2['body'])) . PHP_EOL;

            // Página solicitud enviada
            $r3 = curlRequest($base . '/solicitud-enviada/' . $cr->uuid);
            echo sprintf("  %s Página solicitud enviada -> %d", ($r3['status'] < 400 ? '✅' : '❌'), $r3['status']) . PHP_EOL;

            $cr->delete();
            echo "  ✅ Solicitud de prueba eliminada" . PHP_EOL;
        } else {
            echo "  ❌ Solicitud NO guardada en BD" . PHP_EOL;
        }
    } else {
        echo "  ⚠️  Faltan datos: ct=" . ($customerType?'✅':'❌') . " dm=" . ($deliveryMethod?'✅':'❌') . " pm=" . ($paymentMethod?'✅':'❌') . " st=" . ($state?'✅':'❌') . " cy=" . ($city?'✅':'❌') . PHP_EOL;
    }
} else {
    echo "  ❌ No CSRF token" . PHP_EOL;
}

echo PHP_EOL . "=== TEST CATÁLOGO CON FILTROS ===" . PHP_EOL;
$filterUrls = [
    '/catalogo?category=implantologia',
    '/catalogo?brand=ab',
    '/catalogo?tag=new',
    '/catalogo?tag=on_sale',
    '/catalogo?tag=featured',
    '/catalogo?q=implante',
    '/catalogo?sort=price_asc',
    '/catalogo?sort=price_desc',
    '/catalogo?sort=name_asc',
    '/catalogo?page=1',
    '/catalogo?page=2',
];
foreach ($filterUrls as $url) {
    $r = curlRequest($base . $url);
    $hasError = str_contains($r['body'], 'exception') || str_contains($r['body'], 'ErrorException');
    echo sprintf("  %s %s -> %d%s", ($r['status'] < 400 && !$hasError ? '✅' : '❌'), $url, $r['status'], $hasError ? ' [ERROR]' : '') . PHP_EOL;
}

echo PHP_EOL . "=== TEST RECURSOS CON FILTROS ===" . PHP_EOL;
$resourceUrls = [
    '/recursos-clinicos?page=1',
    '/recursos-clinicos?sort=recent',
    '/recursos-clinicos?sort=position',
];
foreach ($resourceUrls as $url) {
    $r = curlRequest($base . $url);
    $hasError = str_contains($r['body'], 'exception');
    echo sprintf("  %s %s -> %d%s", ($r['status'] < 400 && !$hasError ? '✅' : '❌'), $url, $r['status'], $hasError ? ' [ERROR]' : '') . PHP_EOL;
}

echo PHP_EOL . "=== TEST RUTA INEXISTENTE (404) ===" . PHP_EOL;
$r = curlRequest($base . '/pagina-que-no-existe');
echo sprintf("  %s /pagina-que-no-existe -> %d", ($r['status'] == 404 ? '✅' : '❌'), $r['status']) . PHP_EOL;

echo PHP_EOL . "=== VERIFICAR MENÚ HEADER ===" . PHP_EOL;
$menus = App\Models\Menus::getHeaderItems();
foreach ($menus as $m) {
    $hasName = !empty(trim($m->name ?? ''));
    echo sprintf("  %s pos=%s name='%s' url='%s'", ($hasName ? '✅' : '❌'), $m->position, $m->name ?? '', $m->url) . PHP_EOL;
}

echo PHP_EOL . "=== VERIFICAR ENLACES INTERNOS EN HOME ===" . PHP_EOL;
@unlink($cookieFile);
$r = curlRequest($base . '/', null, $cookieFile);
preg_match_all('/href="([^"]*)"/', $r['body'], $links);
$uniqueLinks = array_unique($links[1]);
$broken = [];
$checked = 0;
foreach ($uniqueLinks as $link) {
    if (preg_match('/^(http|https|#|mailto:|tel:|wa\.me)/', $link)) continue;
    if (str_starts_with($link, '/')) {
        $checked++;
        $r2 = curlRequest($base . $link);
        if ($r2['status'] >= 400) {
            $broken[] = "$link -> " . $r2['status'];
        }
    }
}
echo "  Enlaces internos verificados: $checked" . PHP_EOL;
if (empty($broken)) {
    echo "  ✅ No se encontraron enlaces rotos" . PHP_EOL;
} else {
    foreach ($broken as $b) echo "  ❌ $b" . PHP_EOL;
}

echo PHP_EOL . "=== VERIFICAR IMÁGENES EN HOME ===" . PHP_EOL;
preg_match_all('/src="([^"]*\.(webp|png|jpg|jpeg|svg|gif)?)"/i', $r['body'], $imgs);
$uniqueImgs = array_unique($imgs[1]);
$imgBroken = [];
$imgChecked = 0;
foreach ($uniqueImgs as $img) {
    if (preg_match('/^(http|https)/', $img) && !str_contains($img, '127.0.0.1')) continue;
    $imgChecked++;
    $fullUrl = str_starts_with($img, '/') ? $base . $img : $img;
    $r2 = curlRequest($fullUrl);
    if ($r2['status'] >= 400 || strlen($r2['body']) < 100) {
        $imgBroken[] = "$img -> " . $r2['status'] . " (" . strlen($r2['body']) . " bytes)";
    }
}
echo "  Imágenes verificadas: $imgChecked" . PHP_EOL;
if (empty($imgBroken)) {
    echo "  ✅ No se encontraron imágenes rotas" . PHP_EOL;
} else {
    foreach ($imgBroken as $b) echo "  ❌ $b" . PHP_EOL;
}

@unlink($cookieFile);
