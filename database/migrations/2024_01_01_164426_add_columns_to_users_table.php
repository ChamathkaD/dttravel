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
            $table->string('emergency_contact2')
                ->nullable()
                ->after('emergency_contact1');

            $table->after('business_logo_path', function (Blueprint $table) {
                $table->string('call_name')->nullable();
                $table->date('dob')->nullable();
                $table->string('gender')->nullable();
                $table->string('passport_id')->nullable();
                $table->date('date_of_delivery')->nullable();
                $table->date('date_of_expire')->nullable();
                $table->string('language')->nullable();
                $table->string('whatsapp')->nullable();
                $table->string('instagram')->nullable();
                $table->string('tiktok')->nullable();
                $table->string('facebook')->nullable();
                $table->string('drug_status')->nullable();
                $table->string('food_status')->nullable();
                $table->text('diet')->nullable();
                $table->text('medication')->nullable();
                $table->text('particular')->nullable();
                $table->text('note')->nullable();
            });

            $table->after('country', function (Blueprint $table) {
                $table->string('state')->nullable();
                $table->string('zip')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'call_name',
                'dob',
                'gender',
                'passport_id',
                'date_of_delivery',
                'date_of_expire',
                'language',
                'whatsapp',
                'instagram',
                'tiktok',
                'facebook',
                'drug_status',
                'food_status',
                'diet',
                'medication',
                'particular',
                'note',
                'country',
                'emergency_contact1',
                'emergency_contact2',
                'state',
                'zip',
                'country',
                'address',
            ]);
        });
    }
};
