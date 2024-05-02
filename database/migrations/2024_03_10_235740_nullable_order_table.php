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
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('adult_count')->nullable()->change();
            $table->integer('child_count')->nullable()->change();
            $table->float('price_per_adult')->nullable()->change();
            $table->float('price_per_child')->nullable()->change();
            $table->float('adult_discount')->nullable()->change();
            $table->float('child_discount')->nullable()->change();
            $table->float('adult_tax')->nullable()->change();
            $table->float('child_tax')->nullable()->change();
            $table->float('adult_net_price')->nullable()->change();
            $table->float('child_net_price')->nullable()->change();
            $table->float('amount')->nullable()->change();
            $table->float('total_tax')->nullable()->change();
            $table->float('total_discount')->nullable()->change();
            $table->float('net_amount')->nullable()->change();
            $table->float('total_price')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

        });
    }
};
