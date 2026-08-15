<?php

namespace Database\Factories;

use App\Models\ResourceSpecialty;
use App\Models\ResourceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'description' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['case_study', 'video', 'manual', 'technical_sheet', 'guide']),
            'format' => $this->faker->randomElement(['article', 'pdf', 'video']),
            'file_path' => $this->faker->filePath(),
            'url' => $this->faker->optional()->url(),
            'position' => $this->faker->numberBetween(0, 20),
            'featured' => false,
            'is_active' => true,
            'resource_type_id' => ResourceType::factory(),
            'resource_specialty_id' => ResourceSpecialty::factory(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attrs) => ['featured' => true]);
    }
}
