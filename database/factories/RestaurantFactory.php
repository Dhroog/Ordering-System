<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'location' => fake()->streetName(),
            'description' => fake()->sentence(),
            'lat' => fake()->latitude(-0.05,0.05),
            'lng' => fake()->longitude(-0.01,0.01),
        ];
    }
}
