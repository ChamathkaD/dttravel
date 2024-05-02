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
        Schema::create('booking_travelers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('created_by');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('call_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('passport_id')->nullable();
            $table->date('date_of_delivery')->nullable();
            $table->date('date_of_expire')->nullable();
            $table->string('language')->nullable();
            $table->string('traveler_img')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('instagram_user_name')->nullable();
            $table->string('tiktok_user_name')->nullable();
            $table->string('facebook_user_name')->nullable();
            $table->string('emergency_contact1')->nullable();
            $table->string('emergency_contact2')->nullable();
            $table->string('drug_status')->nullable();
            $table->string('food_status')->nullable();
            $table->string('diet')->nullable();
            $table->string('meditation')->nullable();
            $table->string('particular')->nullable();
            $table->text('note')->nullable();
            $table->string('flight_number')->nullable();
            $table->date('flight_date')->nullable();
            $table->time('dispatcher_time')->nullable();
            $table->string('dispatcher_airport')->nullable();
            $table->time('arrival_time')->nullable();
            $table->string('arrival_airport')->nullable();
            $table->string('relationship')->nullable();
            $table->string('relationship_traveler')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_travelers');
    }
};
