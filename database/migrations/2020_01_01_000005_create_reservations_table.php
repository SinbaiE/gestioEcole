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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
            $table->string('reservation_number')->unique();
            $table->dateTime('check_in_date');
            $table->dateTime('check_out_date');
            $table->integer('number_of_adults');
            $table->integer('number_of_children');
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
        Schema::dropIfExists('reservations');
    }
};
