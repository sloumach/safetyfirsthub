<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->unsignedBigInteger('user_id'); // Référence à l'utilisateur
            $table->unsignedBigInteger('product_id'); // Référence au produit
            $table->timestamps(); // Pour created_at et updated_at

            // Clés étrangères et contraintes
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('courses')->onDelete('cascade');
            $table->unique(['user_id', 'product_id']); // Empêche les doublons pour un utilisateur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
