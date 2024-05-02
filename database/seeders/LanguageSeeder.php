<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = public_path('sql/languages.sql');
        $sql = file_get_contents($filePath);

        // Execute the SQL queries
        DB::unprepared($sql);
    }
}
