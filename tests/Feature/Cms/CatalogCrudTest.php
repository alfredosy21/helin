<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\BrandsController;
use App\Http\Controllers\Cms\CategoriesController;
use App\Http\Controllers\Cms\LineController;
use App\Http\Controllers\Cms\ProductPlatformsController;
use App\Http\Controllers\Cms\SystemProductsController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Line;
use App\Models\ProductPlatform;
use App\Models\SystemProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class CatalogCrudTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    public function test_category_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(CategoriesController::class)
            ->call('create')
            ->set('name', 'Implantes')
            ->set('slug', 'implantes')
            ->set('description', 'Descripción de prueba')
            ->set('seo_keywords', 'implantes, dental')
            ->set('is_featured', true)
            ->call('save')
            ->assertHasNoErrors();

        $category = Category::where('slug', 'implantes')->first();
        $this->assertNotNull($category);
        $this->assertSame('Implantes', $category->name);
        $this->assertTrue((bool) $category->is_featured);
        $this->assertSame(1, $category->order);

        Livewire::actingAs($admin)
            ->test(CategoriesController::class)
            ->call('edit', $category->id)
            ->assertSet('name', 'Implantes')
            ->set('name', 'Implantes Avanzados')
            ->set('is_featured', false)
            ->call('save')
            ->assertHasNoErrors();

        $category->refresh();
        $this->assertSame('Implantes Avanzados', $category->name);
        $this->assertFalse((bool) $category->is_featured);

        Livewire::actingAs($admin)
            ->test(CategoriesController::class)
            ->call('confirmDelete', $category->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_brand_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(BrandsController::class)
            ->call('create')
            ->set('name', '3M')
            ->set('seo_keywords', '3m, adhesivos')
            ->set('banner_title', 'Banner 3M')
            ->call('save')
            ->assertHasNoErrors();

        $brand = Brand::where('name', '3M')->first();
        $this->assertNotNull($brand);
        $this->assertSame('Banner 3M', $brand->banner_title);
        $this->assertSame('3m, adhesivos', $brand->seo_keywords);

        Livewire::actingAs($admin)
            ->test(BrandsController::class)
            ->call('edit', $brand->id)
            ->assertSet('editingId', $brand->id)
            ->set('name', '3M Dental')
            ->assertSet('name', '3M Dental')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $this->assertSame('3M Dental', $brand->refresh()->name);
        $this->assertDatabaseCount('brands', 1);

        Livewire::actingAs($admin)
            ->test(BrandsController::class)
            ->call('confirmDelete', $brand->id);

        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    public function test_line_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(LineController::class)
            ->call('create')
            ->set('name', 'Instrumental')
            ->set('seo_keywords', 'instrumental, acero')
            ->call('save')
            ->assertHasNoErrors();

        $line = Line::where('name', 'Instrumental')->first();
        $this->assertNotNull($line);

        Livewire::actingAs($admin)
            ->test(LineController::class)
            ->call('edit', $line->id)
            ->set('name', 'Instrumental Quirúrgico')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Instrumental Quirúrgico', $line->refresh()->name);

        Livewire::actingAs($admin)
            ->test(LineController::class)
            ->call('confirmDelete', $line->id);

        $this->assertDatabaseMissing('lines', ['id' => $line->id]);
    }

    public function test_system_product_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(SystemProductsController::class)
            ->call('create')
            ->set('name', 'Odontología Digital')
            ->set('seo_keywords', 'digital, cad-cam')
            ->set('banner_description', 'Banner digital')
            ->call('save')
            ->assertHasNoErrors();

        $system = SystemProduct::where('name', 'Odontología Digital')->first();
        $this->assertNotNull($system);
        $this->assertSame('Banner digital', $system->banner_description);

        Livewire::actingAs($admin)
            ->test(SystemProductsController::class)
            ->call('edit', $system->id)
            ->set('name', 'Odontología Digital Avanzada')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertSame('Odontología Digital Avanzada', $system->refresh()->name);

        Livewire::actingAs($admin)
            ->test(SystemProductsController::class)
            ->call('delete', $system->id);

        $this->assertDatabaseMissing('system_products', ['id' => $system->id]);
    }

    public function test_product_platform_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(ProductPlatformsController::class)
            ->call('create')
            ->set('name', 'Web')
            ->set('banner_title', 'Plataforma web')
            ->call('save')
            ->assertHasNoErrors();

        $platform = ProductPlatform::where('name', 'Web')->first();
        $this->assertNotNull($platform);
        $this->assertSame('Plataforma web', $platform->banner_title);

        Livewire::actingAs($admin)
            ->test(ProductPlatformsController::class)
            ->call('edit', $platform->id)
            ->set('name', 'Web + App')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertSame('Web + App', $platform->refresh()->name);

        Livewire::actingAs($admin)
            ->test(ProductPlatformsController::class)
            ->call('delete', $platform->id);

        $this->assertDatabaseMissing('product_platforms', ['id' => $platform->id]);
    }
}
