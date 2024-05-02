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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('traveler_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('travel_package_id')->constrained('travel_packages')->cascadeOnDelete();
            $table->date('travel_date');
            $table->decimal('total_adults_price')->default(0.00);
            $table->decimal('total_child_price')->default(0.00);
            $table->decimal('total_price')->default(0.00);
            $table->integer('order_no');
            $table->string('note_title')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['InProgress', 'Completed', 'Cancel'])->default('InProgress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
