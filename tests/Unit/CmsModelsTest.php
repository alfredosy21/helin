<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Line;
use App\Models\SystemProduct;
use App\Models\ProductPlatform;
use App\Models\ResourceType;
use App\Models\ResourceSpecialty;
use App\Models\Resource;
use App\Models\Settings;
use App\Models\ContactMessage;
use App\Models\PageSeo;
use PHPUnit\Framework\TestCase;

class CmsModelsTest extends TestCase
{
    public function test_category_fillable_and_casts(): void
    {
        $model = new Category();

        $this->assertContains('seo_keywords', $model->getFillable());
        $this->assertContains('image', $model->getFillable());
        $this->assertContains('is_featured', $model->getFillable());
        $this->assertContains('banner_title', $model->getFillable());
        $this->assertContains('banner_description', $model->getFillable());
        $this->assertContains('banner_image', $model->getFillable());
        $this->assertSame('boolean', $model->getCasts()['is_featured']);
        $this->assertSame('boolean', $model->getCasts()['is_active']);
    }

    public function test_brand_fillable_and_casts(): void
    {
        $model = new Brand();

        $this->assertContains('seo_keywords', $model->getFillable());
        $this->assertContains('banner_title', $model->getFillable());
        $this->assertContains('banner_description', $model->getFillable());
        $this->assertContains('banner_image', $model->getFillable());
        $this->assertSame('boolean', $model->getCasts()['is_active']);
    }

    public function test_line_fillable_and_casts(): void
    {
        $model = new Line();

        $this->assertContains('image', $model->getFillable());
        $this->assertContains('seo_keywords', $model->getFillable());
        $this->assertContains('banner_image', $model->getFillable());
    }

    public function test_system_product_fillable_and_casts(): void
    {
        $model = new SystemProduct();

        $this->assertContains('image', $model->getFillable());
        $this->assertContains('seo_keywords', $model->getFillable());
        $this->assertContains('banner_title', $model->getFillable());
        $this->assertContains('banner_description', $model->getFillable());
        $this->assertContains('banner_image', $model->getFillable());
    }

    public function test_product_platform_fillable_and_casts(): void
    {
        $model = new ProductPlatform();

        $this->assertContains('image', $model->getFillable());
        $this->assertContains('seo_keywords', $model->getFillable());
        $this->assertContains('banner_image', $model->getFillable());
    }

    public function test_resource_type_fillable_and_casts(): void
    {
        $model = new ResourceType();

        $this->assertContains('image', $model->getFillable());
        $this->assertContains('banner_title', $model->getFillable());
        $this->assertContains('banner_description', $model->getFillable());
        $this->assertContains('banner_image', $model->getFillable());
        $this->assertSame('boolean', $model->getCasts()['is_active']);
        $this->assertSame('integer', $model->getCasts()['position']);
    }

    public function test_resource_specialty_fillable_and_casts(): void
    {
        $model = new ResourceSpecialty();

        $this->assertContains('banner_title', $model->getFillable());
        $this->assertContains('banner_description', $model->getFillable());
        $this->assertContains('banner_image', $model->getFillable());
    }

    public function test_resource_fillable_and_casts(): void
    {
        $model = new Resource();

        $this->assertContains('content', $model->getFillable());
        $this->assertContains('diagnosis', $model->getFillable());
        $this->assertContains('gallery', $model->getFillable());
        $this->assertContains('video_url', $model->getFillable());
        $this->assertContains('materials', $model->getFillable());
        $this->assertContains('results', $model->getFillable());
        $this->assertNotContains('views', $model->getFillable());
        $this->assertSame('array', $model->getCasts()['gallery']);
        $this->assertSame('boolean', $model->getCasts()['featured']);
    }

    public function test_settings_fillable_and_casts(): void
    {
        $model = new Settings();

        $this->assertContains('opinion_url', $model->getFillable());
        $this->assertContains('offices', $model->getFillable());
        $this->assertSame('array', $model->getCasts()['offices']);
    }

    public function test_contact_message_fillable_and_casts(): void
    {
        $model = new ContactMessage();

        $this->assertContains('nombre', $model->getFillable());
        $this->assertContains('email', $model->getFillable());
        $this->assertContains('telefono', $model->getFillable());
        $this->assertContains('asunto', $model->getFillable());
        $this->assertContains('mensaje', $model->getFillable());
        $this->assertContains('is_read', $model->getFillable());
        $this->assertSame('boolean', $model->getCasts()['is_read']);
    }

    public function test_page_seo_fillable(): void
    {
        $model = new PageSeo();

        $this->assertContains('page_slug', $model->getFillable());
        $this->assertContains('seo_title', $model->getFillable());
        $this->assertContains('seo_description', $model->getFillable());
        $this->assertContains('seo_keywords', $model->getFillable());
        $this->assertContains('og_image', $model->getFillable());
    }
}