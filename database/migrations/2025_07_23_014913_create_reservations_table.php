<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_number')->unique();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained();
            $table->foreignId('room_id')->nullable()->constrained();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('nights');
            $table->decimal('room_rate', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'no_show'])->default('pending');
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'refunded'])->default('pending');
            $table->string('source')->default('direct');
            $table->text('special_requests')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
