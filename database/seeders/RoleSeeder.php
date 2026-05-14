<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder as BaseSeeder;

/**
 * Role Seeder
 *
 * This seeder populates the roles table with the main system roles.
 * Roles are used to manage user permissions and access levels.
 * This seeder should run before UserSeeder to ensure roles exist when creating users.
 */
class RoleSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrador',
                'display_name' => 'Administrator',
                'description' => 'Super administrator with full system access',
                'is_active' => true,
            ],
            [
                'name' => 'Editores',
                'display_name' => 'Editores',
                'description' => 'Content editors with limited access',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            $createdRole = Role::updateOrCreate(
                ['name' => $role['name']],
            );

            // Create permissions for this role
            Permission::createPermissions($createdRole->id);
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
