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
            $table->integer('adult_count')->default(0)->after('travel_date');
            $table->integer('child_count')->default(0)->after('adult_count');

            $table->float('price_per_adult')->default(0)->after('child_count');
            $table->float('price_per_child')->default(0)->after('price_per_adult');

            $table->float('adult_discount')->default(0)->after('total_child_price');
            $table->float('child_discount')->default(0)->after('adult_discount');

            $table->float('adult_tax')->default(0)->after('child_discount');
            $table->float('child_tax')->default(0)->after('adult_tax');

            $table->float('adult_net_price')->default(0)->after('child_tax');
            $table->float('child_net_price')->default(0)->after('adult_net_price');
            $table->float('amount')->default(0)->after('child_net_price');
            $table->float('total_tax')->default(0)->after('amount');
            $table->float('total_discount')->default(0)->after('total_tax');
            $table->float('net_amount')->default(0)->after('total_discount');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('adult_count');
            $table->dropColumn('child_count');
            $table->dropColumn('price_per_adult');
            $table->dropColumn('price_per_child');
            $table->dropColumn('adult_discount');
            $table->dropColumn('child_discount');
            $table->dropColumn('adult_tax');
            $table->dropColumn('child_tax');
            $table->dropColumn('adult_net_price');
            $table->dropColumn('child_net_price');
            $table->dropColumn('amount');
            $table->dropColumn('total_tax');
            $table->dropColumn('total_discount');
            $table->dropColumn('net_amount');
        });
    }
};
