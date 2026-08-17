<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\PageSeoController;
use App\Http\Controllers\Cms\SettingsController;
use App\Models\PageSeo;
use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class SettingsPageSeoCrudTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    public function test_settings_loads_and_saves_with_offices(): void
    {
        $admin = $this->adminUser();
        $this->defaultSettings();

        Livewire::actingAs($admin)
            ->test(SettingsController::class)
            ->assertSet('name', 'Helin')
            ->assertSet('email', 'info@helin.com')
            ->assertSet('offices', []);

        Livewire::actingAs($admin)
            ->test(SettingsController::class)
            ->call('addOffice')
            ->assertSet('offices.0.name', '')
            ->call('addOffice')
            ->call('removeOffice', 0)
            ->assertCount('offices', 1)
            ->set('name', 'Helin Dental')
            ->set('opinion_url', 'https://forms.gle/abc123')
            ->set('offices.0.name', 'Caracas')
            ->set('offices.0.url', 'https://maps.example.com/caracas')
            ->set('offices.0.whatsapp', 'https://wa.me/584241111111')
            ->set('offices.0.active', true)
            ->call('save')
            ->assertHasNoErrors();

        $settings = Settings::find(Settings::DEFAULT_SETTINGS);
        $this->assertSame('Helin Dental', $settings->name);
        $this->assertSame('https://forms.gle/abc123', $settings->opinion_url);
        $this->assertCount(1, $settings->offices);
        $this->assertSame('Caracas', $settings->offices[0]['name']);
        $this->assertTrue($settings->offices[0]['active']);
    }

    public function test_page_seo_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(PageSeoController::class)
            ->call('create')
            ->set('page_slug', 'inicio')
            ->set('seo_title', 'Helin - Inicio')
            ->set('seo_description', 'Descripción SEO de inicio')
            ->set('seo_keywords', 'odontología, implantes')
            ->call('save')
            ->assertHasNoErrors();

        $seo = PageSeo::where('page_slug', 'inicio')->first();
        $this->assertNotNull($seo);
        $this->assertSame('Helin - Inicio', $seo->seo_title);
        $this->assertSame('odontología, implantes', $seo->seo_keywords);

        Livewire::actingAs($admin)
            ->test(PageSeoController::class)
            ->call('edit', $seo->id)
            ->set('seo_title', 'Helin - Inicio Actualizado')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Helin - Inicio Actualizado', $seo->refresh()->seo_title);

        Livewire::actingAs($admin)
            ->test(PageSeoController::class)
            ->call('confirmDelete', $seo->id);

        $this->assertDatabaseMissing('page_seo', ['id' => $seo->id]);
    }

    public function test_page_seo_slug_is_unique(): void
    {
        $admin = $this->adminUser();

        PageSeo::forceCreate([
            'page_slug' => 'inicio',
            'seo_title' => 'Existente',
            'seo_description' => null,
            'seo_keywords' => null,
            'og_image' => null,
        ]);

        Livewire::actingAs($admin)
            ->test(PageSeoController::class)
            ->call('create')
            ->set('page_slug', 'inicio')
            ->set('seo_title', 'Duplicado')
            ->call('save')
            ->assertHasErrors('page_slug');
    }
}
