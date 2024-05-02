<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@demo.com')->first();

        if ($user === null) {
            // Disable observer events during seeding
            User::withoutEvents(function () {
                $user = User::create([
                    'first_name' => 'Super',
                    'last_name' => 'Administrator',
                    'email' => 'admin@demo.com',
                    'password' => Hash::make('admin'),
                ]);

                $user->assignRole('Super Administrator');

                $this->command->info('Here is your super administrator details to login:');
                $this->command->warn($user->email);
                $this->command->warn('Password is "admin"');
            });

        }
    }
}
