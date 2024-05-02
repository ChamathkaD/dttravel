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
            $table->string('business_name')->nullable()->after('profile_photo_path');
            $table->string('business_reg_no')->nullable()->after('business_name');
            $table->string('business_contact')->nullable()->after('business_reg_no');
            $table->string('business_email')->nullable()->after('business_contact');
            $table->string('business_address')->nullable()->after('business_email');
            $table->string('business_country')->nullable()->after('business_address');
            $table->string('business_city')->nullable()->after('business_country');
            $table->string('business_logo_path')->nullable()->after('business_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'business_reg_no',
                'business_contact',
                'business_email',
                'business_address',
                'business_country',
                'business_city',
                'business_logo_path',
            ]);
        });
    }
};
