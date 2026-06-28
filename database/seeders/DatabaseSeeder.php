<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ModuleSeeder::class,
            RoleSeeder::class,
            SettingsSeeder::class,
            SectionSeeder::class,
            UserSeeder::class,
            LineSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            SystemProductSeeder::class,
            ProductPlatformSeeder::class,
            ResourceTypeSeeder::class,
            ResourceSpecialtySeeder::class,
            ResourceSeeder::class,
            TestimonialSeeder::class,
            PaymentMethodSeeder::class,
            MenuSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
