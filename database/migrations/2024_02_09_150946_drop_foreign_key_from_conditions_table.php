<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('conditions', function (Blueprint $table) {
            $table->dropForeign(['travel_package_id']);
            $table->dropColumn('travel_package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conditions', function (Blueprint $table) {
            $table->foreignId('travel_package_id')
                ->after('id')
                ->constrained('travel_packages')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }
};
