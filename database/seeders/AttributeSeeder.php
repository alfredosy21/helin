<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

/**
 * Attribute Seeder
 *
 * This seeder populates the attributes table with predefined attributes
 * for product dimensions/variants management.
 */
class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        Attribute::updateOrCreate(
            ['name' => 'Dimensión'],
            [
                'slug' => 'dimension',
                'description' => 'Dimensión del producto en milímetros',
                'is_active' => true,
            ]
        );
    }
}