<?php

namespace Tests\Feature\Cms;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Settings;
use App\Models\Submodule;
use App\Models\User;
use Database\Seeders\ModuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionSystemTest extends TestCase
{
    use RefreshDatabase;

    private function user(int $level, int $roleId, string $email): User
    {
        return User::forceCreate([
            'name' => 'User Test',
            'email' => $email,
            'password' => bcrypt('password'),
            'level' => $level,
            'rol_id' => $roleId,
            'email_verified_at' => now(),
        ]);
    }

    private function createSettings(): void
    {
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
    }

    public function test_module_seeder_registers_core_modules_with_constant_ids(): void
    {
        $this->seed(ModuleSeeder::class);

        $this->assertDatabaseHas('modules', ['id' => Module::ADMINISTRATORS, 'name' => 'Administradores']);
        $this->assertDatabaseHas('modules', ['id' => Module::SETTINGS, 'name' => 'Configuración']);
        $this->assertDatabaseHas('modules', ['id' => Module::CATALOG, 'name' => 'Catálogo']);
        $this->assertDatabaseHas('modules', ['id' => Module::CONTENT, 'name' => 'Contenido']);
        $this->assertDatabaseHas('modules', ['id' => Module::CONTACT, 'name' => 'Contacto']);

        $this->assertDatabaseHas('submodules', ['id' => Submodule::WEBSITE_MENU, 'name' => 'Menú del Sitio', 'module_id' => Module::SETTINGS]);
        $this->assertDatabaseHas('submodules', ['id' => Submodule::PAGE_SEO, 'module_id' => Module::SETTINGS]);
        $this->assertDatabaseHas('submodules', ['id' => Submodule::COMMERCIAL_REQUESTS, 'module_id' => Module::CONTACT]);

        // Running the seeder again must not duplicate rows
        $this->seed(ModuleSeeder::class);

        $this->assertEquals(5, Module::count());
        $this->assertEquals(24, Submodule::count());
    }

    public function test_create_permissions_is_idempotent(): void
    {
        $this->seed(ModuleSeeder::class);
        Role::forceCreate(['id' => 1, 'name' => 'Administrador']);

        Permission::createPermissions(1);
        Permission::createPermissions(1);

        // 6 main module permissions + 26 submodule permissions, no duplicates
        $this->assertEquals(32, Permission::where('rol_id', 1)->count());
    }

    public function test_editor_with_granted_permissions_can_access_its_modules(): void
    {
        $this->seed(ModuleSeeder::class);
        $this->createSettings();

        $role = Role::forceCreate(['id' => 2, 'name' => 'Editores']);
        $editor = $this->user(2, $role->id, 'editor@test.com');

        // Grant all permissions, then revoke testimonials and general settings
        Permission::createPermissions($role->id);
        Permission::where('rol_id', $role->id)
            ->where('submodule_id', Submodule::TESTIMONIALS)
            ->update(['status' => Permission::INACTIVE_STATUS]);
        Permission::where('rol_id', $role->id)
            ->where('submodule_id', Submodule::GENERAL_SETTINGS)
            ->update(['status' => Permission::INACTIVE_STATUS]);

        $this->actingAs($editor)->get('/cms/catalog/brands')->assertOk();
        $this->actingAs($editor)->get('/cms/testimonials')->assertForbidden();
        $this->actingAs($editor)->get('/cms/settings')->assertForbidden();
    }

    public function test_middleware_resolves_numeric_ids_from_routes(): void
    {
        $this->seed(ModuleSeeder::class);
        $this->createSettings();

        $role = Role::forceCreate(['id' => 2, 'name' => 'Editores']);
        $editor = $this->user(2, $role->id, 'editor@test.com');

        // Grant ONLY the brands permission (module + submodule)
        Permission::create([
            'rol_id' => $role->id,
            'module_id' => Module::CATALOG,
            'type' => Permission::MAIN_MODULE_TYPE,
            'status' => Permission::ACTIVE_STATUS,
        ]);
        Permission::create([
            'rol_id' => $role->id,
            'module_id' => Module::CATALOG,
            'submodule_id' => Submodule::PRODUCT_BRANDS,
            'type' => Permission::SUB_MODULE_TYPE,
            'status' => Permission::ACTIVE_STATUS,
        ]);

        $this->actingAs($editor)->get('/cms/catalog/brands')->assertOk();
        $this->actingAs($editor)->get('/cms/catalog/lines')->assertForbidden();
        $this->actingAs($editor)->get('/cms/testimonials')->assertForbidden();
    }
}
