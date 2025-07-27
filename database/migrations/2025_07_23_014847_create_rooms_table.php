<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->string('room_number')->unique();
            $table->string('floor');
            $table->enum('status', ['available', 'occupied', 'maintenance', 'cleaning', 'out_of_order'])->default('available');
            $table->enum('housekeeping_status', ['clean', 'dirty', 'inspected', 'maintenance'])->default('clean');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
