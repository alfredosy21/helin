<?php

namespace Tests\Feature\Cms\Concerns;

use App\Models\Role;
use App\Models\Settings;
use App\Models\User;

trait CreatesCmsUsers
{
    private function adminUser(): User
    {
        $role = Role::forceCreate(['id' => 1, 'name' => 'Administrador']);

        return User::forceCreate([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'level' => 1,
            'rol_id' => $role->id,
            'email_verified_at' => now(),
        ]);
    }

    private function defaultSettings(): void
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
}
