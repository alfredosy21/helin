<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * User Seeder
 *
 * This seeder creates the initial users for the system.
 * Creates a super administrator user with proper role assignment.
 * This seeder should run after RoleSeeder to ensure roles exist.
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super administrator user
        User::create([
            'name' => 'Administrator Helin',
            'email' => 'admin@helin.com',
            'level' => 1, // Super Administrator level
            'rol_id' => Role::ADMINISTRATOR, // Administrator role
            'password' => 'helin2026', // Hashed password
            'is_active' => true, // Active user
            'email_verified_at' => now(), // Email verified
            'last_login_at' => now(), // Set initial login time
            'image' => null, // No profile image initially
            'phone' => null,
            'department' => null,
            'position' => 'Super Administrator',
            'biography' => 'System super administrator with full access to all modules and settings.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create editor user for testing
        User::create([
            'name' => 'Editor Helin',
            'email' => 'editor@helin.com',
            'level' => 2, // Regular user level
            'rol_id' => Role::EDITOR, // Editor role
            'password' => Hash::make('editor2026'), // Hashed password
            'is_active' => true, // Active user
            'email_verified_at' => now(), // Email verified
            'last_login_at' => null, // No login yet
            'image' => null, // No profile image initially
            'phone' => null,
            'department' => 'Content Management',
            'position' => 'Content Editor',
            'biography' => 'Content editor responsible for managing articles, testimonials, and other content.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Super Admin: admin@helin.com / helin2026');
        $this->command->info('Editor: editor@helin.com / editor2026');
    }
}
