<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$base = 'http://127.0.0.1:8000';

$routes = [
    ['GET', '/', 'home'],
    ['GET', '/catalogo', 'catalogo'],
    ['GET', '/carrito', 'carrito'],
    ['GET', '/contactanos', 'contactanos'],
    ['GET', '/nuestra-empresa', 'nuestra-empresa'],
    ['GET', '/politicas', 'politicas'],
    ['GET', '/recursos-clinicos', 'recursos-clinicos'],
    ['GET', '/solicitud', 'solicitud'],
];

echo "=== RUTAS WEB PÚBLICAS (GET) ===" . PHP_EOL;
foreach ($routes as [$method, $uri, $name]) {
    try {
        $response = Http::timeout(10)->get($base . $uri);
        $status = $response->status();
        $bodyLen = strlen($response->body());
        $hasError = str_contains($response->body(), 'exception') || str_contains($response->body(), 'ErrorException');
        $marker = ($status >= 200 && $status < 400 && !$hasError) ? '✅' : '❌';
        echo sprintf("%s %s %s -> %d (body: %d bytes)%s", $marker, $method, $uri, $status, $bodyLen, $hasError ? ' [ERROR IN BODY]' : '') . PHP_EOL;
    } catch (\Exception $e) {
        echo sprintf("❌ %s %s -> EXCEPTION: %s", $method, $uri, $e->getMessage()) . PHP_EOL;
    }
}

echo PHP_EOL . "=== RUTAS DINÁMICAS (GET) ===" . PHP_EOL;

// Producto
$product = App\Models\Product::where('is_active', true)->first();
if ($product) {
    try {
        $r = Http::timeout(10)->get($base . '/producto/' . $product->slug);
        $hasError = str_contains($r->body(), 'exception') || str_contains($r->body(), 'ErrorException');
        echo sprintf("%s GET /producto/%s -> %d (body: %d)%s", ($r->status() < 400 && !$hasError ? '✅' : '❌'), $product->slug, $r->status(), strlen($r->body()), $hasError ? ' [ERROR]' : '') . PHP_EOL;
    } catch (\Exception $e) {
        echo "❌ GET /producto/{$product->slug} -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "⚠️  No hay productos activos para probar" . PHP_EOL;
}

// Caso clínico
$resource = App\Models\Resource::where('is_active', true)->first();
if ($resource) {
    try {
        $r = Http::timeout(10)->get($base . '/caso-clinico/' . $resource->slug);
        $hasError = str_contains($r->body(), 'exception') || str_contains($r->body(), 'ErrorException');
        echo sprintf("%s GET /caso-clinico/%s -> %d (body: %d)%s", ($r->status() < 400 && !$hasError ? '✅' : '❌'), $resource->slug, $r->status(), strlen($r->body()), $hasError ? ' [ERROR]' : '') . PHP_EOL;
    } catch (\Exception $e) {
        echo "❌ GET /caso-clinico/{$resource->slug} -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "⚠️  No hay recursos activos para probar" . PHP_EOL;
}

// Solicitud enviada (con UUID inválido para ver si maneja el error)
try {
    $r = Http::timeout(10)->get($base . '/solicitud-enviada/invalid-uuid-test');
    echo sprintf("%s GET /solicitud-enviada/{invalid} -> %d", ($r->status() < 500 ? '✅' : '❌'), $r->status()) . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ GET /solicitud-enviada/{invalid} -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
}

// PDF cotización (con UUID inválido)
try {
    $r = Http::timeout(10)->get($base . '/pdf/cotizacion/invalid-uuid-test');
    echo sprintf("%s GET /pdf/cotizacion/{invalid} -> %d", ($r->status() < 500 ? '✅' : '❌'), $r->status()) . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ GET /pdf/cotizacion/{invalid} -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== API AJAX ===" . PHP_EOL;

// Buscador de header
try {
    $r = Http::timeout(10)->get($base . '/api/search/products?q=implante');
    $hasError = str_contains($r->body(), 'exception');
    echo sprintf("%s GET /api/search/products?q=implante -> %d (body: %d)%s", ($r->status() < 400 && !$hasError ? '✅' : '❌'), $r->status(), strlen($r->body()), $hasError ? ' [ERROR]' : '') . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ GET /api/search/products -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
}

// Filtro de productos
try {
    $r = Http::timeout(10)->asForm()->post($base . '/api/products/filter', ['page' => 1]);
    $hasError = str_contains($r->body(), 'exception');
    echo sprintf("%s POST /api/products/filter -> %d (body: %d)%s", ($r->status() < 400 && !$hasError ? '✅' : '❌'), $r->status(), strlen($r->body()), $hasError ? ' [ERROR]' : '') . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ POST /api/products/filter -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
}

// Filtro de recursos
try {
    $r = Http::timeout(10)->get($base . '/api/recursos-clinicos/filtrar?page=1');
    $hasError = str_contains($r->body(), 'exception');
    echo sprintf("%s GET /api/recursos-clinicos/filtrar -> %d (body: %d)%s", ($r->status() < 400 && !$hasError ? '✅' : '❌'), $r->status(), strlen($r->body()), $hasError ? ' [ERROR]' : '') . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ GET /api/recursos-clinicos/filtrar -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
}

// Filtro de recursos (POST)
try {
    $r = Http::timeout(10)->asForm()->post($base . '/api/resources/filter', ['page' => 1]);
    $hasError = str_contains($r->body(), 'exception');
    echo sprintf("%s POST /api/resources/filter -> %d (body: %d)%s", ($r->status() < 400 && !$hasError ? '✅' : '❌'), $r->status(), strlen($r->body()), $hasError ? ' [ERROR]' : '') . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ POST /api/resources/filter -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== FORMULARIOS (POST) ===" . PHP_EOL;

// Contacto - submit válido
try {
    $r = Http::timeout(10)->asForm()->post($base . '/contactanos/send', [
        'nombre' => 'Test User',
        'email' => 'test@test.com',
        'telefono' => '1234567890',
        'asunto' => 'Test asunto',
        'mensaje' => 'Test mensaje de contacto',
        'privacy_accepted' => 'on',
    ]);
    $hasError = str_contains($r->body(), 'exception');
    echo sprintf("%s POST /contactanos/send (válido) -> %d%s", ($r->status() < 400 && !$hasError ? '✅' : '❌'), $r->status(), $hasError ? ' [ERROR]' : '') . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ POST /contactanos/send -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
}

// Contacto - submit inválido (sin campos)
try {
    $r = Http::timeout(10)->asForm()->post($base . '/contactanos/send', []);
    echo sprintf("%s POST /contactanos/send (vacío) -> %d (debe dar error de validación)", ($r->status() >= 300 && $r->status() < 500 ? '✅' : '❌'), $r->status()) . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ POST /contactanos/send (vacío) -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
}

// Solicitud - submit inválido (sin campos)
try {
    $r = Http::timeout(10)->asForm()->post($base . '/solicitud/send', []);
    echo sprintf("%s POST /solicitud/send (vacío) -> %d (debe dar error de validación)", ($r->status() >= 300 && $r->status() < 500 ? '✅' : '❌'), $r->status()) . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ POST /solicitud/send (vacío) -> EXCEPTION: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== CONTENIDO HTML (verificar elementos clave) ===" . PHP_EOL;

// Home
$r = Http::timeout(10)->get($base . '/');
$body = $r->body();
$checks = [
    'logo' => 'logo',
    'hero' => 'hero',
    'categorias' => 'categor',
    'testimonios' => 'testimon',
    'footer' => 'footer',
];
foreach ($checks as $name => $needle) {
    $found = stripos($body, $needle) !== false;
    echo sprintf("%s Home contiene '%s'", ($found ? '✅' : '❌'), $name) . PHP_EOL;
}

// Catálogo
$r = Http::timeout(10)->get($base . '/catalogo');
$body = $r->body();
$checks = [
    'productos' => 'product',
    'filtros' => 'filter',
    'paginación' => 'pag',
    'sidebar' => 'sidebar',
];
foreach ($checks as $name => $needle) {
    $found = stripos($body, $needle) !== false;
    echo sprintf("%s Catálogo contiene '%s'", ($found ? '✅' : '❌'), $name) . PHP_EOL;
}

// Recursos
$r = Http::timeout(10)->get($base . '/recursos-clinicos');
$body = $r->body();
$checks = [
    'recursos' => 'recurso',
    'filtros' => 'filter',
    'tipo' => 'tipo',
    'especialidad' => 'especial',
];
foreach ($checks as $name => $needle) {
    $found = stripos($body, $needle) !== false;
    echo sprintf("%s Recursos contiene '%s'", ($found ? '✅' : '❌'), $name) . PHP_EOL;
}

// Contacto
$r = Http::timeout(10)->get($base . '/contactanos');
$body = $r->body();
$checks = [
    'formulario' => 'form',
    'nombre' => 'nombre',
    'email' => 'email',
    'mensaje' => 'mensaje',
    'whatsapp' => 'whatsapp',
    'privacy' => 'privacy',
];
foreach ($checks as $name => $needle) {
    $found = stripos($body, $needle) !== false;
    echo sprintf("%s Contacto contiene '%s'", ($found ? '✅' : '❌'), $name) . PHP_EOL;
}

// Solicitud
$r = Http::timeout(10)->get($base . '/solicitud');
$body = $r->body();
$checks = [
    'formulario' => 'form',
    'tipo_cliente' => 'tipo_cliente',
    'envio' => 'envio',
    'pago' => 'pago',
    'privacy' => 'privacy',
    'carrito' => 'cart',
];
foreach ($checks as $name => $needle) {
    $found = stripos($body, $needle) !== false;
    echo sprintf("%s Solicitud contiene '%s'", ($found ? '✅' : '❌'), $name) . PHP_EOL;
}

echo PHP_EOL . "=== SEO META TAGS ===" . PHP_EOL;
$r = Http::timeout(10)->get($base . '/');
$body = $r->body();
$seoChecks = [
    '<title>' => '<title>',
    'meta description' => 'name="description"',
    'meta keywords' => 'name="keywords"',
    'og:title' => 'property="og:title"',
    'og:description' => 'property="og:description"',
    'og:image' => 'property="og:image"',
    'twitter:card' => 'name="twitter:card"',
    'favicon' => 'favicon',
];
foreach ($seoChecks as $name => $needle) {
    $found = stripos($body, $needle) !== false;
    echo sprintf("%s Home tiene %s", ($found ? '✅' : '❌'), $name) . PHP_EOL;
}

echo PHP_EOL . "=== FAVICON EN TODAS LAS PÁGINAS ===" . PHP_EOL;
$pages = ['/', '/catalogo', '/contactanos', '/recursos-clinicos', '/nuestra-empresa', '/politicas', '/solicitud', '/carrito'];
foreach ($pages as $uri) {
    $r = Http::timeout(10)->get($base . $uri);
    $hasFavicon = stripos($r->body(), 'favicon') !== false;
    echo sprintf("%s %s tiene favicon", ($hasFavicon ? '✅' : '❌'), $uri) . PHP_EOL;
}
