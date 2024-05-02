<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Do you wish to fresh migration before seeding, it will clear all old data?')) {
            // Call the php artisan migrate:fresh
            $this->command->call('migrate:fresh');
            $this->command->warn('Data cleared, starting from blank database.');
        }

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::table('roles')->delete();

        // Create roles
        Role::updateOrCreate(['name' => 'Super Administrator', 'role_level' => 1]);
        Role::updateOrCreate(['name' => 'Staff Member', 'role_level' => 2]);
        Role::updateOrCreate(['name' => 'Travel Agent', 'role_level' => 3]);
        Role::updateOrCreate(['name' => 'Traveler', 'role_level' => 4]);

        $this->command->info('Default Roles added.');
    }
}
