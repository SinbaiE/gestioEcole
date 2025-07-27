<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['spa', 'restaurant', 'bar', 'laundry', 'transport', 'business_center', 'fitness', 'room_service', 'other']);
            $table->decimal('price', 10, 2);
            $table->enum('pricing_type', ['fixed', 'per_hour', 'per_day', 'per_person']);
            $table->boolean('is_active')->default(true);
            $table->json('availability_schedule')->nullable();
            $table->integer('max_capacity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
