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
        Schema::create('service_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->foreignId('reservation_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('service_date');
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_bookings');
    }
};
