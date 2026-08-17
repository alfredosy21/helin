<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\DashboardController;
use App\Http\Controllers\Cms\ProfileController;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class DashboardProfileTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    public function test_dashboard_refreshes_stats(): void
    {
        $admin = $this->adminUser();
        Category::forceCreate([
            'name' => 'Implantes',
            'slug' => 'implantes',
            'is_active' => 1,
            'order' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(DashboardController::class)
            ->call('refreshStats')
            ->assertSet('stats.total_categories', 1)
            ->assertSet('stats.total_products', 0);
    }

    public function test_profile_updates_current_user(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(ProfileController::class)
            ->set('name', 'Admin Renombrado')
            ->set('position', 'Supervisor CMS')
            ->call('save');

        $this->assertSame('Admin Renombrado', $admin->refresh()->name);
        $this->assertSame('Supervisor CMS', $admin->position);
    }

    public function test_profile_password_change_rejects_wrong_current_password(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(ProfileController::class)
            ->set('current_password', 'wrong-password')
            ->set('new_password', 'newpassword123')
            ->set('new_password_confirmation', 'newpassword123')
            ->call('savePassword')
            ->assertHasErrors(['current_password']);

        $this->assertTrue($admin->refresh()->password !== bcrypt('newpassword123'));
    }

    public function test_profile_password_change_succeeds_with_valid_current_password(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(ProfileController::class)
            ->set('current_password', 'password')
            ->set('new_password', 'newpassword123')
            ->set('new_password_confirmation', 'newpassword123')
            ->call('savePassword')
            ->assertHasNoErrors();

        $this->assertTrue(password_verify('newpassword123', $admin->refresh()->password));
    }
}
