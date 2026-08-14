<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\PermissionsController;
use App\Http\Controllers\Cms\RolController;
use App\Http\Controllers\Cms\UserController;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Submodule;
use App\Models\User;
use Database\Seeders\ModuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    public function test_user_crud_flow(): void
    {
        $admin = $this->adminUser();
        $role = Role::forceCreate(['id' => 2, 'name' => 'Editores']);

        Livewire::actingAs($admin)
            ->test(UserController::class)
            ->call('create')
            ->set('name', 'Editor Uno')
            ->set('email', 'editor1@test.com')
            ->set('rol_id', $role->id)
            ->set('password', 'password123')
            ->call('save')
            ->assertHasNoErrors();

        $user = User::where('email', 'editor1@test.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('Editor Uno', $user->name);
        $this->assertSame(2, $user->level);
        $this->assertSame($role->id, $user->rol_id);

        Livewire::actingAs($admin)
            ->test(UserController::class)
            ->call('edit', $user->id)
            ->assertSet('name', 'Editor Uno')
            ->set('name', 'Editor Uno Actualizado')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Editor Uno Actualizado', $user->refresh()->name);

        Livewire::actingAs($admin)
            ->test(UserController::class)
            ->call('confirmDelete', $user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_requires_password_on_create_only(): void
    {
        $admin = $this->adminUser();
        $role = Role::forceCreate(['id' => 2, 'name' => 'Editores']);

        Livewire::actingAs($admin)
            ->test(UserController::class)
            ->call('create')
            ->set('name', 'Sin Password')
            ->set('email', 'sinpass@test.com')
            ->set('rol_id', $role->id)
            ->call('save')
            ->assertHasErrors(['password']);

        $this->assertDatabaseMissing('users', ['email' => 'sinpass@test.com']);
    }

    public function test_role_crud_flow_creates_permissions_automatically(): void
    {
        $this->seed(ModuleSeeder::class);
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(RolController::class)
            ->call('create')
            ->set('name', 'Editores de Contenido')
            ->call('save')
            ->assertHasNoErrors();

        $role = Role::where('name', 'Editores de Contenido')->first();
        $this->assertNotNull($role);
        $this->assertSame(32, Permission::where('rol_id', $role->id)->count());

        Livewire::actingAs($admin)
            ->test(RolController::class)
            ->call('edit', $role->id)
            ->set('name', 'Editores Avanzados')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Editores Avanzados', $role->refresh()->name);

        Livewire::actingAs($admin)
            ->test(RolController::class)
            ->call('confirmDelete', $role->id);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_system_role_is_protected_from_edit(): void
    {
        $this->seed(ModuleSeeder::class);
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(RolController::class)
            ->call('edit', Role::ADMINISTRATOR)
            ->assertNotSet('editingId', Role::ADMINISTRATOR)
            ->assertDispatched('toast');
    }

    public function test_permissions_controller_toggles_modules_and_submodules(): void
    {
        $this->seed(ModuleSeeder::class);
        $admin = $this->adminUser();
        $role = Role::forceCreate(['id' => 2, 'name' => 'Editores']);
        Permission::createPermissions($role->id);

        // Toggle module off: main module and its submodules go inactive
        Livewire::actingAs($admin)
            ->test(PermissionsController::class, ['roleId' => $role->id])
            ->call('toggleModulePermission', Module::CATALOG);

        $this->assertSame(
            Permission::INACTIVE_STATUS,
            Permission::where('rol_id', $role->id)
                ->where('module_id', Module::CATALOG)
                ->whereNull('submodule_id')
                ->value('status')
        );

        // Submodule toggle requires the parent module active: no change while off
        $brandPermission = Permission::where('rol_id', $role->id)
            ->where('submodule_id', Submodule::PRODUCT_BRANDS)
            ->firstOrFail();
        $before = $brandPermission->status;

        Livewire::actingAs($admin)
            ->test(PermissionsController::class, ['roleId' => $role->id])
            ->call('toggleSubmodulePermission', $brandPermission->id, Permission::INACTIVE_STATUS);

        $this->assertSame($before, $brandPermission->refresh()->status);

        // Turn the module back on, then submodule toggle works
        Livewire::actingAs($admin)
            ->test(PermissionsController::class, ['roleId' => $role->id])
            ->call('toggleModulePermission', Module::CATALOG);

        Livewire::actingAs($admin)
            ->test(PermissionsController::class, ['roleId' => $role->id])
            ->call('toggleSubmodulePermission', $brandPermission->id, Permission::ACTIVE_STATUS);

        $this->assertSame(
            Permission::INACTIVE_STATUS,
            $brandPermission->refresh()->status
        );
    }
}
