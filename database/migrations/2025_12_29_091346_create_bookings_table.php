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
            
            // Foreign keys
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('place_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_spot_id')->constrained()->onDelete('cascade');
            
            // Booking details
            $table->string('vehicle_plate', 20);
            $table->integer('duration_hours');
            
            // Time tracking
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            
            // Payment
            $table->decimal('total_price', 10, 2); // 10 digits total, 2 decimal places
            $table->string('payment_method')->default('cash'); // 'card' or 'cash'
            
            // Status: active, reserved, completed, cancelled
            $table->string('status', 20)->default('active');
            
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['parking_spot_id', 'status']);
            $table->index(['start_time', 'end_time']);
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
