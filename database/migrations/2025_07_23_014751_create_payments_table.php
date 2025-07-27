<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('guest_id')->constrained();
            $table->string('payment_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'orange_money', 'mtn_mobile_money', 'other']);
            $table->string('transaction_reference')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->datetime('payment_date');
            $table->text('notes')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
