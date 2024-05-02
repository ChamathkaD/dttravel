<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get a random user with role 'Agent'
        $agentUser = User::role('Travel Agent')->inRandomOrder()->first();

        // Get a random user with role 'Traveler'
        $travelerUser = User::role('Traveler')->inRandomOrder()->first();

        // Get a random travel package
        $travelPackage = TravelPackage::inRandomOrder()->first();

        $total_adults_price = fake()->randomNumber(5);
        $total_child_price = fake()->randomNumber(2);

        return [
            'agent_id' => $agentUser->id,
            'traveler_id' => $travelerUser->id,
            'travel_package_id' => $travelPackage->id,
            'travel_date' => fake()->date(),
            'total_adults_price' => $total_adults_price,
            'total_child_price' => $total_child_price,
            'total_price' => $total_adults_price + $total_child_price,
            'order_no' => fake()->unique()->randomNumber(),
            'note_title' => fake()->sentence(),
            'note' => fake()->paragraph(),
            'status' => fake()->randomElement(['InProgress', 'Completed', 'Cancel']),
            'created_at' => fake()->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
