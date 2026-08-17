<?php

namespace Tests\Feature\Cms;

use App\Models\Role;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsAccessTest extends TestCase
{
    use RefreshDatabase;

    private array $cmsUrls = [
        '/cms/catalog/family',
        '/cms/catalog/brands',
        '/cms/catalog/lines',
        '/cms/catalog/system-products',
        '/cms/catalog/product-platforms',
        '/cms/resource-types',
        '/cms/resource-specialties',
        '/cms/resources',
        '/cms/settings',
        '/cms/page-seo',
    ];

    private function admin(): User
    {
        $role = Role::create(['name' => 'Administrador']);

        return User::forceCreate([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'level' => 1,
            'rol_id' => $role->id,
            'email_verified_at' => now(),
        ]);
    }

    private function editor(): User
    {
        $role = Role::create(['name' => 'Editor']);

        return User::forceCreate([
            'name' => 'Editor Test',
            'email' => 'editor@test.com',
            'password' => bcrypt('password'),
            'level' => 2,
            'rol_id' => $role->id,
            'email_verified_at' => now(),
        ]);
    }

    public function test_guest_is_redirected_to_login_for_all_cms_urls(): void
    {
        foreach ($this->cmsUrls as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_editor_is_forbidden_for_all_cms_urls(): void
    {
        $editor = $this->editor();

        foreach ($this->cmsUrls as $url) {
            $this->actingAs($editor)->get($url)->assertForbidden();
        }
    }

    public function test_admin_can_access_all_cms_urls(): void
    {
        $admin = $this->admin();

        Settings::forceCreate([
            'id' => Settings::DEFAULT_SETTINGS,
            'name' => 'Helin',
            'email' => 'info@helin.com',
            'address' => '',
            'tagline' => '',
            'contact_address' => '',
            'phone' => '',
            'shedule' => '',
            'copy' => '',
            'facebook' => '',
            'instagram' => '',
            'linkedin' => '',
            'youtube' => '',
            'keywords' => '',
            'description' => '',
            'settings_description' => '',
            'analytics_code' => '',
            'opinion_url' => '',
        ]);

        foreach ($this->cmsUrls as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }
}
