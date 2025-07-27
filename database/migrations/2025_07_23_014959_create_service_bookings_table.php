<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('guest_id')->constrained();
            $table->foreignId('reservation_id')->nullable()->constrained();
            $table->string('booking_number')->unique();
            $table->datetime('service_date');
            $table->time('service_time')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('guests_count')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_bookings');
    }
};
