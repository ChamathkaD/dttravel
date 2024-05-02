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
        Schema::create('travel_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_destination_id')
                ->constrained('travel_destinations')
                ->cascadeOnDelete();
            $table->foreignId('travel_category_id')
                ->constrained('travel_categories')
                ->cascadeOnDelete();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->decimal('adults_price', 10, 2);
            $table->decimal('child_price', 10, 2);
            $table->decimal('discounted_price', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->integer('min_pax')->default(0);
            $table->integer('max_pax')->default(0);
            $table->boolean('is_charge_tax')->default(false);
            $table->boolean('is_published')->default(true);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keyphrase')->nullable();
            $table->string('meta_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_packages');
    }
};
