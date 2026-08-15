<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'specialty' => $this->faker->jobTitle(),
            'content' => $this->faker->paragraph(),
            'is_active' => true,
            'position' => $this->faker->numberBetween(0, 10),
        ];
    }
}
