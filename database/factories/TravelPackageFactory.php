<?php

namespace Database\Factories;

use App\Models\TravelPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TravelPackage>
 */
class TravelPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'travel_destination_id' => fake()->randomNumber(2, true),
            'travel_category_id' => fake()->randomNumber(2, true),
            'name' => fake()->company(),
            'description' => fake()->realText(150),
            'adults_price' => fake()->randomNumber(4),
            'child_price' => fake()->randomNumber(3),
            'discounted_price' => fake()->randomNumber(2),
            'tax' => fake()->randomNumber(2),
            'min_pax' => fake()->randomNumber(1),
            'max_pax' => fake()->randomNumber(1),
            'is_charge_tax' => fake()->randomElement([1, 0]),
            'is_published' => fake()->randomElement([1, 0]),
            'meta_title' => fake()->sentence(2),
            'meta_description' => fake()->sentence(3),
            'meta_keyphrase' => fake()->sentence(2),
        ];
    }
}
