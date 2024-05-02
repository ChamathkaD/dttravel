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
        Schema::table('travel_packages', function (Blueprint $table) {
            $table->decimal('adults_price', 10, 2)->nullable()->change();
            $table->decimal('child_price', 10, 2)->nullable()->change();
            $table->decimal('discounted_price', 10, 2)->nullable()->change();
            $table->decimal('tax', 10, 2)->nullable()->change();
            $table->integer('min_pax')->default(0)->nullable()->change();
            $table->integer('max_pax')->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_packages', function (Blueprint $table) {
            $table->decimal('adults_price', 10, 2);
            $table->decimal('child_price', 10, 2);
            $table->decimal('discounted_price', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->integer('min_pax')->default(0);
            $table->integer('max_pax')->default(0);
        });
    }
};
