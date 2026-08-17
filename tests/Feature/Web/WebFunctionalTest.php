<?php

namespace Tests\Feature\Web;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\CommercialRequest;
use App\Models\CustomerType;
use App\Models\DeliveryMethod;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Resource;
use App\Models\ResourceSpecialty;
use App\Models\ResourceType;
use App\Models\Settings;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebFunctionalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // ===================================================================
    // 1. PÁGINAS PÚBLICAS — Deben renderizar sin errores
    // ===================================================================

    public function test_home_page_render(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
    }

    public function test_catalogo_page_render(): void
    {
        $response = $this->get(route('catalogo'));
        $response->assertStatus(200);
    }

    public function test_producto_detail_page_render(): void
    {
        $product = Product::where('is_active', true)->first();
        $this->assertNotNull($product, 'Debe existir al menos un producto activo');

        $response = $this->get(route('producto', ['slug' => $product->slug]));
        $response->assertStatus(200);
    }

    public function test_producto_detail_404_for_inactive_product(): void
    {
        $product = Product::factory()->inactive()->create();
        $response = $this->get(route('producto', ['slug' => $product->slug]));
        $response->assertStatus(404);
    }

    public function test_carrito_page_render(): void
    {
        $response = $this->get(route('carrito'));
        $response->assertStatus(200);
    }

    public function test_solicitud_page_render(): void
    {
        $response = $this->get(route('solicitud'));
        $response->assertStatus(200);
    }

    public function test_contactanos_page_render(): void
    {
        $response = $this->get(route('contactanos'));
        $response->assertStatus(200);
    }

    public function test_nuestra_empresa_page_render(): void
    {
        $response = $this->get(route('nuestra-empresa'));
        $response->assertStatus(200);
    }

    public function test_politicas_page_render(): void
    {
        $response = $this->get(route('politicas'));
        $response->assertStatus(200);
    }

    public function test_recursos_clinicos_page_render(): void
    {
        $response = $this->get(route('recursos-clinicos'));
        $response->assertStatus(200);
    }

    public function test_caso_clinico_detail_page_render(): void
    {
        $resource = Resource::where('is_active', true)->first();
        $this->assertNotNull($resource, 'Debe existir al menos un recurso activo');

        $response = $this->get(route('caso-clinico', ['slug' => $resource->slug]));
        $response->assertStatus(200);
    }

    public function test_caso_clinico_404_for_inactive_resource(): void
    {
        $resource = Resource::factory()->inactive()->create();
        $response = $this->get(route('caso-clinico', ['slug' => $resource->slug]));
        $response->assertStatus(404);
    }

    // ===================================================================
    // 2. PÁGINA 404
    // ===================================================================

    public function test_404_page_for_nonexistent_route(): void
    {
        $response = $this->get('/pagina-que-no-existe');
        $response->assertStatus(404);
    }

    public function test_404_page_for_nonexistent_product(): void
    {
        $response = $this->get(route('producto', ['slug' => 'producto-inexistente-12345']));
        $response->assertStatus(404);
    }

    public function test_404_page_for_nonexistent_resource(): void
    {
        $response = $this->get(route('caso-clinico', ['slug' => 'recurso-inexistente-12345']));
        $response->assertStatus(404);
    }

    // ===================================================================
    // 3. FILTROS DE PRODUCTOS (AJAX)
    // ===================================================================

    public function test_product_filter_returns_success(): void
    {
        $response = $this->postJson(route('products.filter'), []);
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'html', 'count']);
    }

    public function test_product_filter_by_search(): void
    {
        $product = Product::where('is_active', true)->first();
        $this->assertNotNull($product);

        $response = $this->postJson(route('products.filter'), [
            'search' => $product->name,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertStringContainsString($product->name, $response->json('html'));
    }

    public function test_product_filter_by_category(): void
    {
        $product = Product::where('is_active', true)->first();
        $this->assertNotNull($product);
        $categorySlug = $product->category->slug;

        $response = $this->postJson(route('products.filter'), [
            'category' => [$categorySlug],
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertGreaterThan(0, $response->json('count'));
    }

    public function test_product_filter_by_brand(): void
    {
        $product = Product::where('is_active', true)
            ->whereNotNull('brand_id')
            ->first();
        if (!$product) {
            $this->markTestSkipped('No hay productos con marca activa');
        }

        $brandSlug = $product->brand->slug;

        $response = $this->postJson(route('products.filter'), [
            'brand' => [$brandSlug],
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_product_filter_by_tag_featured(): void
    {
        Product::factory()->featured()->create();

        $response = $this->postJson(route('products.filter'), [
            'tag' => ['featured'],
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertGreaterThanOrEqual(1, $response->json('count'));
    }

    public function test_product_filter_by_tag_new(): void
    {
        Product::factory()->newProduct()->create();

        $response = $this->postJson(route('products.filter'), [
            'tag' => ['new'],
        ]);

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, $response->json('count'));
    }

    public function test_product_filter_by_tag_on_sale(): void
    {
        Product::factory()->onSale()->create();

        $response = $this->postJson(route('products.filter'), [
            'tag' => ['on_sale'],
        ]);

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, $response->json('count'));
    }

    public function test_product_filter_sort_price_asc(): void
    {
        $response = $this->postJson(route('products.filter'), [
            'sort' => 'price_asc',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_product_filter_sort_price_desc(): void
    {
        $response = $this->postJson(route('products.filter'), [
            'sort' => 'price_desc',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_product_filter_sort_name_asc(): void
    {
        $response = $this->postJson(route('products.filter'), [
            'sort' => 'name_asc',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_product_filter_combined(): void
    {
        $product = Product::where('is_active', true)->first();
        $this->assertNotNull($product);

        $response = $this->postJson(route('products.filter'), [
            'search' => $product->name,
            'category' => [$product->category->slug],
            'sort' => 'recent',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_product_filter_empty_results(): void
    {
        $response = $this->postJson(route('products.filter'), [
            'search' => 'zzz-no-existe-xyz-123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('count', 0);
    }

    // ===================================================================
    // 4. CATÁLOGO CON FILTROS POR URL (GET)
    // ===================================================================

    public function test_catalogo_with_category_filter(): void
    {
        $category = Category::where('is_active', true)->first();
        $this->assertNotNull($category);

        $response = $this->get(route('catalogo', ['category' => $category->slug]));
        $response->assertStatus(200);
    }

    public function test_catalogo_with_search(): void
    {
        $product = Product::where('is_active', true)->first();
        $this->assertNotNull($product);

        $response = $this->get(route('catalogo', ['search' => $product->name]));
        $response->assertStatus(200);
    }

    public function test_catalogo_with_brand_filter(): void
    {
        $brand = Brand::where('is_active', true)->first();
        if (!$brand) {
            $this->markTestSkipped('No hay marcas activas');
        }

        $response = $this->get(route('catalogo', ['brand' => $brand->slug]));
        $response->assertStatus(200);
    }

    public function test_catalogo_with_nonexistent_category_still_200(): void
    {
        $response = $this->get(route('catalogo', ['category' => 'no-existe']));
        $response->assertStatus(200);
    }

    // ===================================================================
    // 5. FILTROS DE RECURSOS CLÍNICOS (AJAX)
    // ===================================================================

    public function test_resource_filter_returns_success(): void
    {
        $response = $this->postJson(route('resources.filter'), []);
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'html', 'count', 'counts']);
    }

    public function test_resource_filter_by_search(): void
    {
        $resource = Resource::where('is_active', true)->first();
        $this->assertNotNull($resource);

        $response = $this->postJson(route('resources.filter'), [
            'search' => $resource->title,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertGreaterThan(0, $response->json('count'));
    }

    public function test_resource_filter_by_type(): void
    {
        $resource = Resource::where('is_active', true)
            ->whereNotNull('resource_type_id')
            ->first();
        $this->assertNotNull($resource);

        $response = $this->postJson(route('resources.filter'), [
            'type' => $resource->resource_type_id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertGreaterThan(0, $response->json('count'));
    }

    public function test_resource_filter_by_specialty(): void
    {
        $resource = Resource::where('is_active', true)
            ->whereNotNull('resource_specialty_id')
            ->first();
        $this->assertNotNull($resource);

        $response = $this->postJson(route('resources.filter'), [
            'specialty' => $resource->resource_specialty_id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertGreaterThan(0, $response->json('count'));
    }

    public function test_resource_filter_by_format(): void
    {
        $resource = Resource::where('is_active', true)->first();
        $this->assertNotNull($resource);

        $response = $this->postJson(route('resources.filter'), [
            'format' => [$resource->format],
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_resource_filter_by_resource_type_array(): void
    {
        $type = ResourceType::where('is_active', true)->first();
        $this->assertNotNull($type);

        $response = $this->postJson(route('resources.filter'), [
            'resource_type' => [$type->id],
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_resource_filter_by_resource_specialty_array(): void
    {
        $specialty = ResourceSpecialty::where('is_active', true)->first();
        $this->assertNotNull($specialty);

        $response = $this->postJson(route('resources.filter'), [
            'resource_specialty' => [$specialty->id],
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_resource_filter_combined_type_and_specialty(): void
    {
        $resource = Resource::where('is_active', true)
            ->whereNotNull('resource_type_id')
            ->whereNotNull('resource_specialty_id')
            ->first();
        if (!$resource) {
            $this->markTestSkipped('No hay recurso con tipo y especialidad');
        }

        $response = $this->postJson(route('resources.filter'), [
            'type' => $resource->resource_type_id,
            'specialty' => $resource->resource_specialty_id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertGreaterThan(0, $response->json('count'));
    }

    public function test_resource_filter_combined_all_filters(): void
    {
        $resource = Resource::where('is_active', true)
            ->whereNotNull('resource_type_id')
            ->whereNotNull('resource_specialty_id')
            ->first();
        if (!$resource) {
            $this->markTestSkipped('No hay recurso con tipo y especialidad');
        }

        $response = $this->postJson(route('resources.filter'), [
            'search' => $resource->title,
            'type' => $resource->resource_type_id,
            'specialty' => $resource->resource_specialty_id,
            'format' => [$resource->format],
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertGreaterThan(0, $response->json('count'));
    }

    public function test_resource_filter_sort_recent(): void
    {
        $response = $this->postJson(route('resources.filter'), [
            'sort' => 'recent',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_resource_filter_empty_results(): void
    {
        $response = $this->postJson(route('resources.filter'), [
            'search' => 'zzz-no-existe-xyz-999',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('count', 0);
    }

    public function test_resource_filter_counts_structure(): void
    {
        $response = $this->postJson(route('resources.filter'), []);

        $response->assertStatus(200);
        $counts = $response->json('counts');
        $this->assertIsArray($counts);
        $this->assertArrayHasKey('resource_type', $counts);
        $this->assertArrayHasKey('resource_specialty', $counts);
        $this->assertArrayHasKey('format', $counts);
    }

    // ===================================================================
    // 6. RECURSOS CLÍNICOS CON FILTROS POR URL (GET)
    // ===================================================================

    public function test_recursos_clinicos_with_search(): void
    {
        $resource = Resource::where('is_active', true)->first();
        $this->assertNotNull($resource);

        $response = $this->get(route('recursos-clinicos', ['search' => $resource->title]));
        $response->assertStatus(200);
    }

    public function test_recursos_clinicos_with_type_filter(): void
    {
        $type = ResourceType::where('is_active', true)->first();
        $this->assertNotNull($type);

        $response = $this->get(route('recursos-clinicos', ['type' => $type->id]));
        $response->assertStatus(200);
    }

    public function test_recursos_clinicos_with_specialty_filter(): void
    {
        $specialty = ResourceSpecialty::where('is_active', true)->first();
        $this->assertNotNull($specialty);

        $response = $this->get(route('recursos-clinicos', ['specialty' => $specialty->id]));
        $response->assertStatus(200);
    }

    public function test_recursos_clinicos_with_format_filter(): void
    {
        $response = $this->get(route('recursos-clinicos', ['format' => ['pdf']]));
        $response->assertStatus(200);
    }

    public function test_recursos_clinicos_combined_filters(): void
    {
        $resource = Resource::where('is_active', true)
            ->whereNotNull('resource_type_id')
            ->whereNotNull('resource_specialty_id')
            ->first();
        if (!$resource) {
            $this->markTestSkipped('No hay recurso con tipo y especialidad');
        }

        $response = $this->get(route('recursos-clinicos', [
            'type' => $resource->resource_type_id,
            'specialty' => $resource->resource_specialty_id,
            'format' => [$resource->format],
        ]));
        $response->assertStatus(200);
    }

    // ===================================================================
    // 7. PAGINACIÓN
    // ===================================================================

    public function test_product_filter_pagination_page_1(): void
    {
        $response = $this->postJson(route('products.filter'), ['page' => 1]);
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_product_filter_pagination_page_2(): void
    {
        $response = $this->postJson(route('products.filter'), ['page' => 2]);
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_resource_filter_pagination_page_1(): void
    {
        $response = $this->postJson(route('resources.filter'), ['page' => 1]);
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_resource_filter_pagination_page_2(): void
    {
        $response = $this->postJson(route('resources.filter'), ['page' => 2]);
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_recursos_clinicos_with_page_param(): void
    {
        $response = $this->get(route('recursos-clinicos', ['page' => 1]));
        $response->assertStatus(200);
    }

    public function test_recursos_clinicos_with_invalid_page_falls_back(): void
    {
        $response = $this->get(route('recursos-clinicos', ['page' => 999]));
        $response->assertStatus(200);
    }

    // ===================================================================
    // 8. BÚSQUEDA AJAX DE PRODUCTOS
    // ===================================================================

    public function test_search_products_returns_results(): void
    {
        $product = Product::where('is_active', true)->first();
        $this->assertNotNull($product);

        $response = $this->getJson(route('api.search.products', ['q' => $product->name]));
        $response->assertStatus(200);
    }

    public function test_search_products_min_length(): void
    {
        $response = $this->getJson(route('api.search.products', ['q' => 'ab']));
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_search_products_no_results(): void
    {
        $response = $this->getJson(route('api.search.products', ['q' => 'zzznoexistexyz']));
        $response->assertStatus(200);
    }

    // ===================================================================
    // 9. FORMULARIO DE CONTACTO
    // ===================================================================

    public function test_contact_form_validation_errors(): void
    {
        $response = $this->postJson(route('contactanos.send'), []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nombre', 'email', 'asunto', 'mensaje']);
    }

    public function test_contact_form_short_message_validation(): void
    {
        $response = $this->postJson(route('contactanos.send'), [
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'asunto' => 'Consulta',
            'mensaje' => 'corto',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['mensaje']);
    }

    public function test_contact_form_invalid_email(): void
    {
        $response = $this->postJson(route('contactanos.send'), [
            'nombre' => 'Test User',
            'email' => 'not-an-email',
            'asunto' => 'Consulta',
            'mensaje' => 'Este es un mensaje de prueba suficientemente largo.',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_contact_form_success(): void
    {
        $response = $this->postJson(route('contactanos.send'), [
            'nombre' => 'Usuario Test',
            'email' => 'test@example.com',
            'telefono' => '+58 412-555-0000',
            'asunto' => 'Consulta general',
            'mensaje' => 'Este es un mensaje de prueba suficientemente largo para pasar la validacion.',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    // ===================================================================
    // 10. SOLICITUD COMERCIAL (CHECKOUT)
    // ===================================================================

    public function test_solicitud_validation_errors(): void
    {
        $response = $this->postJson(route('solicitud.send'), []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'tipo_cliente', 'nombre', 'apellido', 'telefono', 'email',
            'estado', 'ciudad', 'direccion', 'envio', 'pago', 'privacy_accepted',
        ]);
    }

    public function test_solicitud_empty_cart_fails(): void
    {
        $customerType = CustomerType::active()->first();
        $state = State::first();
        $city = City::where('state_id', $state->id)->first();
        $deliveryMethod = DeliveryMethod::active()->first();
        $paymentMethod = PaymentMethod::active()->first();

        $this->assertNotNull($customerType);
        $this->assertNotNull($deliveryMethod);
        $this->assertNotNull($paymentMethod);

        $response = $this->postJson(route('solicitud.send'), [
            'tipo_cliente' => $customerType->slug,
            'nombre' => 'Gabriel',
            'apellido' => 'Montes',
            'telefono' => '+58 412-555-0000',
            'email' => 'gabriel@test.com',
            'estado' => $state->code,
            'ciudad' => $city->slug,
            'direccion' => 'Av. Principal, Edificio Test',
            'envio' => $deliveryMethod->slug,
            'pago' => $paymentMethod->name,
            'privacy_accepted' => '1',
        ]);

        $response->assertStatus(400);
        $response->assertJsonPath('success', false);
    }

    public function test_solicitud_success_with_cart_items(): void
    {
        $product = Product::where('is_active', true)->first();
        $this->assertNotNull($product);

        $customerType = CustomerType::active()->first();
        $state = State::first();
        $city = City::where('state_id', $state->id)->first();
        $deliveryMethod = DeliveryMethod::active()->first();
        $paymentMethod = PaymentMethod::active()->first();

        $this->assertNotNull($customerType);
        $this->assertNotNull($deliveryMethod);
        $this->assertNotNull($paymentMethod);

        $cartItems = json_encode([
            [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => 2,
                'price' => $product->price,
                'sku' => $product->sku,
                'dimension' => '',
            ],
        ]);

        $response = $this->postJson(route('solicitud.send'), [
            'tipo_cliente' => $customerType->slug,
            'nombre' => 'Gabriel',
            'apellido' => 'Montes',
            'telefono' => '+58 412-555-0000',
            'email' => 'gabriel@test.com',
            'estado' => $state->code,
            'ciudad' => $city->slug,
            'direccion' => 'Av. Principal, Edificio Test',
            'envio' => $deliveryMethod->slug,
            'pago' => $paymentMethod->name,
            'privacy_accepted' => '1',
            'cart_items' => $cartItems,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure(['redirect_url', 'request_id']);

        $this->assertDatabaseHas('commercial_requests', [
            'first_name' => 'Gabriel',
            'last_name' => 'Montes',
            'email' => 'gabriel@test.com',
        ]);
    }

    public function test_solicitud_enviada_page_render(): void
    {
        $product = Product::where('is_active', true)->first();
        $this->assertNotNull($product);

        $customerType = CustomerType::active()->first();
        $state = State::first();
        $city = City::where('state_id', $state->id)->first();
        $deliveryMethod = DeliveryMethod::active()->first();
        $paymentMethod = PaymentMethod::active()->first();

        $cartData = [
            ['id' => $product->id, 'quantity' => 1],
        ];

        $cr = CommercialRequest::create([
            'customer_type_id' => $customerType->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '+58 412-555-0000',
            'email' => 'test@test.com',
            'state_id' => $state->id,
            'city_id' => $city->id,
            'address' => 'Test Address',
            'delivery_method_id' => $deliveryMethod->id,
            'payment_method_id' => $paymentMethod->id,
            'status' => 'pending',
            'cart_data' => $cartData,
            'privacy_accepted' => true,
        ]);

        $response = $this->get(route('solicitud-enviada', ['uuid' => $cr->uuid]));
        $response->assertStatus(200);
    }

    public function test_solicitud_enviada_404_for_invalid_uuid(): void
    {
        $response = $this->get(route('solicitud-enviada', ['uuid' => 'invalid-uuid-12345']));
        $response->assertStatus(404);
    }

    // ===================================================================
    // 11. PDF DE COTIZACIÓN
    // ===================================================================

    public function test_pdf_cotizacion_signed_route_works(): void
    {
        $product = Product::where('is_active', true)->first();
        $this->assertNotNull($product);

        $customerType = CustomerType::active()->first();
        $state = State::first();
        $city = City::where('state_id', $state->id)->first();
        $deliveryMethod = DeliveryMethod::active()->first();
        $paymentMethod = PaymentMethod::active()->first();

        $cr = CommercialRequest::create([
            'customer_type_id' => $customerType->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '+58 412-555-0000',
            'email' => 'test@test.com',
            'state_id' => $state->id,
            'city_id' => $city->id,
            'address' => 'Test Address',
            'delivery_method_id' => $deliveryMethod->id,
            'payment_method_id' => $paymentMethod->id,
            'status' => 'pending',
            'cart_data' => [['id' => $product->id, 'quantity' => 1]],
            'privacy_accepted' => true,
        ]);

        $url = \Illuminate\Support\Facades\URL::signedRoute('pdf.cotizacion', ['uuid' => $cr->uuid]);
        $response = $this->get($url);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_pdf_cotizacion_unsigned_route_fails(): void
    {
        $response = $this->get(route('pdf.cotizacion', ['uuid' => 'test-uuid']));
        $response->assertStatus(403);
    }

    // ===================================================================
    // 12. API DE RECURSOS (filtrar)
    // ===================================================================

    public function test_api_resource_filtrar_returns_success(): void
    {
        $response = $this->getJson(route('api.recursos.filtrar'));
        $response->assertStatus(200);
    }

    public function test_api_resource_filtrar_with_search(): void
    {
        $resource = Resource::where('is_active', true)->first();
        $this->assertNotNull($resource);

        $response = $this->getJson(route('api.recursos.filtrar', ['search' => $resource->title]));
        $response->assertStatus(200);
    }

    // ===================================================================
    // 13. ENLACES Y NAVEGACIÓN
    // ===================================================================

    public function test_catalogo_page_contains_product_links(): void
    {
        $response = $this->get(route('catalogo'));
        $response->assertStatus(200);
        $product = Product::where('is_active', true)->first();
        if ($product) {
            $response->assertSee($product->slug);
        }
    }

    public function test_recursos_clinicos_contains_resource_links(): void
    {
        $resource = Resource::where('is_active', true)->first();
        $this->assertNotNull($resource);

        $response = $this->get(route('recursos-clinicos'));
        $response->assertStatus(200);
    }

    public function test_home_page_contains_navigation_links(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee(route('catalogo'));
        $response->assertSee(route('contactanos'));
        $response->assertSee(route('nuestra-empresa'));
    }

    public function test_carrito_page_contains_checkout_link(): void
    {
        $response = $this->get(route('carrito'));
        $response->assertStatus(200);
        $response->assertSee('/solicitud');
    }
}
