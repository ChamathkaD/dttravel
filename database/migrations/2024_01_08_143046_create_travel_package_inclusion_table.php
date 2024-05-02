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
        Schema::create('travel_package_inclusion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_package_id')
                ->references('id')
                ->on('travel_packages')
                ->cascadeOnDelete();
            $table->foreignId('inclusion_id')
                ->references('id')
                ->on('inclusions')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_package_inclusion');
    }
};
