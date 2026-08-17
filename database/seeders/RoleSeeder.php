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
                'id' => Role::ADMINISTRATOR,
                'name' => 'Administrador',
            ],
            [
                'id' => Role::EDITOR,
                'name' => 'Editores',
            ],
        ];

        foreach ($roles as $role) {
            $createdRole = Role::updateOrCreate(
                ['id' => $role['id']],
                $role
            );

            // Create permissions for this role
            Permission::createPermissions($createdRole->id);
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
