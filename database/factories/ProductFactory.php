<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'name' => ucfirst($name),
            'slug' => \Illuminate\Support\Str::slug($name),
            'sku' => strtoupper($this->faker->unique()->bothify('??-####')),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'currency' => 'USD',
            'stock' => $this->faker->numberBetween(0, 100),
            'unit' => 'unidad',
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'is_active' => true,
            'is_featured' => false,
            'is_new' => false,
            'is_on_sale' => false,
            'view_count' => 0,
            'search_count' => 0,
            'rating' => 0,
            'review_count' => 0,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attrs) => ['is_featured' => true]);
    }

    public function newProduct(): static
    {
        return $this->state(fn (array $attrs) => ['is_new' => true]);
    }

    public function onSale(): static
    {
        return $this->state(fn (array $attrs) => [
            'is_on_sale' => true,
            'sale_price' => $this->faker->randomFloat(2, 5, 250),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => false]);
    }
}
