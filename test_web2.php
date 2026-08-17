<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;
$base = 'http://127.0.0.1:8000';

echo "=== VERIFICAR og:image ===" . PHP_EOL;
$settings = App\Models\Settings::getSettings();
echo "settings->image: " . ($settings->image ?? 'NULL') . PHP_EOL;
$pageSeo = App\Models\PageSeo::where('page_slug', 'home')->first();
echo "pageSeo(home)->og_image: " . ($pageSeo->og_image ?? 'NULL') . PHP_EOL;

echo PHP_EOL . "=== EXTRACTAR META TAGS DE HOME ===" . PHP_EOL;
$r = Http::timeout(10)->get($base . '/');
$body = $r->body();
preg_match('/<title>(.*?)<\/title>/is', $body, $m);
echo "Title: " . ($m[1] ?? 'NOT FOUND') . PHP_EOL;
preg_match('/<meta name="description" content="(.*?)">/is', $body, $m);
echo "Description: " . substr($m[1] ?? 'NOT FOUND', 0, 80) . PHP_EOL;
preg_match('/<meta name="keywords" content="(.*?)">/is', $body, $m);
echo "Keywords: " . substr($m[1] ?? 'NOT FOUND', 0, 80) . PHP_EOL;
preg_match('/<meta property="og:title" content="(.*?)">/is', $body, $m);
echo "og:title: " . ($m[1] ?? 'NOT FOUND') . PHP_EOL;
preg_match('/<meta property="og:image" content="(.*?)">/is', $body, $m);
echo "og:image: " . ($m[1] ?? 'NOT FOUND') . PHP_EOL;

echo PHP_EOL . "=== EXTRACTAR META TAGS DE CATÁLOGO ===" . PHP_EOL;
$r = Http::timeout(10)->get($base . '/catalogo');
$body = $r->body();
preg_match('/<title>(.*?)<\/title>/is', $body, $m);
echo "Title: " . ($m[1] ?? 'NOT FOUND') . PHP_EOL;
preg_match('/<meta name="description" content="(.*?)">/is', $body, $m);
echo "Description: " . substr($m[1] ?? 'NOT FOUND', 0, 80) . PHP_EOL;

echo PHP_EOL . "=== EXTRACTAR META TAGS DE CONTACTO ===" . PHP_EOL;
$r = Http::timeout(10)->get($base . '/contactanos');
$body = $r->body();
preg_match('/<title>(.*?)<\/title>/is', $body, $m);
echo "Title: " . ($m[1] ?? 'NOT FOUND') . PHP_EOL;
preg_match('/<meta name="description" content="(.*?)">/is', $body, $m);
echo "Description: " . substr($m[1] ?? 'NOT FOUND', 0, 80) . PHP_EOL;

echo PHP_EOL . "=== EXTRACTAR META TAGS DE RECURSOS ===" . PHP_EOL;
$r = Http::timeout(10)->get($base . '/recursos-clinicos');
$body = $r->body();
preg_match('/<title>(.*?)<\/title>/is', $body, $m);
echo "Title: " . ($m[1] ?? 'NOT FOUND') . PHP_EOL;
preg_match('/<meta name="description" content="(.*?)">/is', $body, $m);
echo "Description: " . substr($m[1] ?? 'NOT FOUND', 0, 80) . PHP_EOL;

echo PHP_EOL . "=== PRODUCTOS: verificar datos ===" . PHP_EOL;
$products = App\Models\Product::where('is_active', true)->take(5)->get();
foreach ($products as $p) {
    $imgOk = !empty($p->main_image_url);
    $catOk = $p->category ? true : false;
    $brandOk = $p->brand ? true : false;
    echo sprintf("  %s | img=%s cat=%s brand=%s price=%s", $p->name, ($imgOk?'✅':'❌'), ($catOk?'✅':'❌'), ($brandOk?'✅':'❌'), $p->price) . PHP_EOL;
}

echo PHP_EOL . "=== RECURSOS: verificar datos ===" . PHP_EOL;
$resources = App\Models\Resource::where('is_active', true)->take(5)->get();
foreach ($resources as $r) {
    $imgOk = !empty($r->image_url);
    $typeOk = $r->resourceType ? true : false;
    $specOk = $r->resourceSpecialty ? true : false;
    echo sprintf("  %s | img=%s type=%s spec=%s", $r->title, ($imgOk?'✅':'❌'), ($typeOk?'✅':'❌'), ($specOk?'✅':'❌')) . PHP_EOL;
}

echo PHP_EOL . "=== TESTIMONIOS: verificar datos ===" . PHP_EOL;
$testimonials = App\Models\Testimonial::where('is_active', true)->take(5)->get();
foreach ($testimonials as $t) {
    $imgOk = !empty($t->image);
    echo sprintf("  %s | img=%s specialty=%s", $t->name, ($imgOk?'✅':'❌'), $t->specialty) . PHP_EOL;
}

echo PHP_EOL . "=== CATEGORÍAS: verificar datos ===" . PHP_EOL;
$cats = App\Models\Category::where('is_active', true)->take(5)->get();
foreach ($cats as $c) {
    $imgOk = !empty($c->image);
    echo sprintf("  %s | img=%s order=%s", $c->name, ($imgOk?'✅':'❌'), $c->order) . PHP_EOL;
}

echo PHP_EOL . "=== MARCAS: verificar datos ===" . PHP_EOL;
$brands = App\Models\Brand::where('is_active', true)->take(5)->get();
foreach ($brands as $b) {
    $imgOk = !empty($b->image);
    echo sprintf("  %s | img=%s order=%s", $b->name, ($imgOk?'✅':'❌'), $b->order) . PHP_EOL;
}

echo PHP_EOL . "=== MÉTODOS DE ENTREGA ===" . PHP_EOL;
$dm = App\Models\DeliveryMethod::where('is_active', true)->orderBy('order')->get();
foreach ($dm as $d) {
    echo sprintf("  %s (slug=%s, order=%s)", $d->name, $d->slug, $d->order) . PHP_EOL;
}

echo PHP_EOL . "=== MÉTODOS DE PAGO ===" . PHP_EOL;
$pm = App\Models\PaymentMethod::where('is_active', true)->orderBy('position')->get();
foreach ($pm as $p) {
    echo sprintf("  %s (receipt=%s, position=%s)", $p->name, $p->requires_receipt, $p->position) . PHP_EOL;
}

echo PHP_EOL . "=== TIPOS DE CLIENTE ===" . PHP_EOL;
$ct = App\Models\CustomerType::where('is_active', true)->orderBy('order')->get();
foreach ($ct as $c) {
    echo sprintf("  %s (slug=%s, order=%s)", $c->name, $c->slug, $c->order) . PHP_EOL;
}

echo PHP_EOL . "=== WHATSAPP NUMBERS ===" . PHP_EOL;
$wa = App\Models\WhatsAppNumber::where('is_active', true)->get();
foreach ($wa as $w) {
    echo sprintf("  %s | exec=%s | phone=%s", $w->name ?? 'N/A', $w->executive_name ?? 'N/A', $w->phone_number) . PHP_EOL;
}

echo PHP_EOL . "=== SECCIONES ACTIVAS ===" . PHP_EOL;
$sections = App\Models\Sections::where('status', 1)->get();
foreach ($sections as $s) {
    echo sprintf("  id=%s | title=%s | status=%s", $s->id, substr($s->title ?? 'N/A', 0, 40), $s->status) . PHP_EOL;
}

echo PHP_EOL . "=== MENÚ HEADER ===" . PHP_EOL;
$menus = App\Models\Menus::getHeaderItems();
foreach ($menus as $m) {
    echo sprintf("  %s -> %s (pos=%s)", $m->name, $m->url, $m->position) . PHP_EOL;
}

echo PHP_EOL . "=== PAGE SEO RECORDS ===" . PHP_EOL;
$ps = App\Models\PageSeo::all();
foreach ($ps as $p) {
    $hasDesc = !empty($p->seo_description);
    $hasKw = !empty($p->seo_keywords);
    $hasOg = !empty($p->og_image);
    echo sprintf("  %s | title=%s desc=%s kw=%s og=%s", $p->page_slug, ($p->seo_title?'✅':'❌'), ($hasDesc?'✅':'❌'), ($hasKw?'✅':'❌'), ($hasOg?'✅':'❌')) . PHP_EOL;
}

echo PHP_EOL . "=== SOLICITUDES COMERCIALES (últimas 3) ===" . PHP_EOL;
$crs = App\Models\CommercialRequest::latest()->take(3)->get();
foreach ($crs as $cr) {
    echo sprintf("  %s | %s | %s | status=%s", $cr->correlative, $cr->full_name, $cr->email, $cr->status) . PHP_EOL;
}

echo PHP_EOL . "=== MENSAJES DE CONTACTO (últimos 3) ===" . PHP_EOL;
$cms = App\Models\ContactMessage::latest()->take(3)->get();
foreach ($cms as $cm) {
    echo sprintf("  %s | %s | %s", $cm->nombre, $cm->email, substr($cm->asunto ?? 'N/A', 0, 30)) . PHP_EOL;
}
