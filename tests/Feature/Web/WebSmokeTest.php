<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_without_errors(): void
    {
        $this->seed();
        $settings = \App\Models\Settings::getSettings();
        $settings->update([
            'opinion_url' => 'https://form.typeform.com/to/R6GXcbEJ',
            'valencia_whatsapp' => '+58 424-466-9150',
            'phone' => '+58 424 466 9150',
            'email' => 'hola@helin.company',
            'copy' => null,
            'caracas_whatsapp' => '+58 424-278-9481',
        ]);

        $product = \App\Models\Product::where('is_active', true)->first();
        $resource = \App\Models\Resource::where('is_active', true)->first();

        $pages = [
            route('home'),
            route('catalogo'),
            route('catalogo', ['category' => $product?->category?->slug ?? 'implantologia']),
            route('producto', ['slug' => $product?->slug ?? 'test']),
            route('solicitud'),
            route('contactanos'),
            route('nuestra-empresa'),
            route('politicas'),
            route('recursos-clinicos'),
            route('caso-clinico', ['slug' => $resource?->slug ?? 'test']),
        ];

        foreach ($pages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }
}