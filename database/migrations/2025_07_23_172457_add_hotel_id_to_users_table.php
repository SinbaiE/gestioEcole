<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('hotel_id')->after('id');

            // Facultatif : si tu veux ajouter une contrainte de clé étrangère
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']); // si la contrainte est ajoutée
            $table->dropColumn('hotel_id');
        });
    }
};
