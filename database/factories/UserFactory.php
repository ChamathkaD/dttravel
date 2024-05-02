<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'status' => fake()->randomElement(['Invited', 'Pending', 'Active', 'Inactive']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'country' => fake()->country(),
            'dob' => fake()->date(),
            'call_name' => fake()->firstName(),
            'business_name' => fake()->company(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Assign random roles to the user
            $roles = Role::exists()
                ? Role::pluck('name')->toArray()
                : ['Super Administrator', 'Staff Member', 'Travel Agent', 'Traveler'];
            $user->assignRole(fake()->randomElement($roles));
            $user->agent_id = User::inRandomOrder()->value('id');
            $user->save();
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
