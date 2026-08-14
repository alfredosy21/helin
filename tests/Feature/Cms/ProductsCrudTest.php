<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\ProductsController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class ProductsCrudTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    private function taxonomy(): array
    {
        $category = Category::forceCreate([
            'name' => 'Implantes',
            'slug' => 'implantes',
            'is_active' => 1,
            'order' => 1,
        ]);
        $brand = Brand::forceCreate([
            'name' => '3M',
            'slug' => '3m',
            'is_active' => 1,
        ]);

        return [$category, $brand];
    }

    public function test_product_crud_flow(): void
    {
        $admin = $this->adminUser();
        [$category, $brand] = $this->taxonomy();

        Livewire::actingAs($admin)
            ->test(ProductsController::class)
            ->call('create')
            ->set('name', 'Implante Cónico Helin')
            ->set('sku', 'imp-001')
            ->set('category_id', $category->id)
            ->set('brand_id', $brand->id)
            ->set('price', 195.50)
            ->set('stock', 25)
            ->set('is_active', true)
            ->call('save')
            ->assertHasNoErrors();

        $product = Product::where('sku', 'IMP-001')->first();
        $this->assertNotNull($product);
        $this->assertSame('Implante Cónico Helin', $product->name);
        $this->assertSame('implante-conico-helin', $product->slug);
        $this->assertSame(195.5, (float) $product->price);
        $this->assertSame(25, (int) $product->stock);
        $this->assertSame($category->id, $product->category_id);
        $this->assertSame($brand->id, $product->brand_id);

        Livewire::actingAs($admin)
            ->test(ProductsController::class)
            ->call('edit', $product->id)
            ->assertSet('name', 'Implante Cónico Helin')
            ->set('name', 'Implante Cónico Helin Plus')
            ->set('price', 210.00)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Implante Cónico Helin Plus', $product->refresh()->name);
        $this->assertSame(210.0, (float) $product->price);

        Livewire::actingAs($admin)
            ->test(ProductsController::class)
            ->call('openDeleteModal', $product->id)
            ->call('delete');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_requires_sku_category_and_brand(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(ProductsController::class)
            ->call('create')
            ->set('name', 'Producto Incompleto')
            ->call('save')
            ->assertHasErrors(['sku', 'category_id', 'brand_id']);

        $this->assertDatabaseCount('products', 0);
    }
}
