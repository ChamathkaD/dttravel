<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Booking;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            CountrySeeder::class,
            LanguageSeeder::class,
        ]);

        // Disable observer events during seeding
        User::withoutEvents(function () {
            User::factory()->count(100)->create();
        });

        TravelPackage::factory()->count(10)->create();

        Booking::factory()->count(250)->create();
    }
}
