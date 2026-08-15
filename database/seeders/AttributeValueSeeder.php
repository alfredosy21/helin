<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

/**
 * AttributeValue Seeder
 *
 * This seeder populates the attribute_values table with predefined values
 * for the Dimensión attribute (Ø3.3 mm, Ø4.1 mm, Ø4.8 mm).
 */
class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        AttributeValue::updateOrCreate(
            ['attribute_id' => 1, 'value' => '3.3', 'label' => 'Ø3.3 mm'],
            [
                'is_active' => true,
            ]
        );

        AttributeValue::updateOrCreate(
            ['attribute_id' => 1, 'value' => '4.1', 'label' => 'Ø4.1 mm'],
            [
                'is_active' => true,
            ]
        );

        AttributeValue::updateOrCreate(
            ['attribute_id' => 1, 'value' => '4.8', 'label' => 'Ø4.8 mm'],
            [
                'is_active' => true,
            ]
        );
    }
}