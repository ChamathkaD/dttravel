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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('business_country', 'country');
            $table->renameColumn('emergency_contact', 'emergency_contact1');
            $table->renameColumn('business_address', 'address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('country', 'business_country');
            $table->renameColumn('emergency_contact1', 'emergency_contact');
            $table->renameColumn('address', 'business_address');
        });
    }
};
