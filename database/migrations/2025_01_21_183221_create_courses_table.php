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
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); // ID unique pour chaque cours
            $table->string('name');
            $table->decimal('price', 8, 2); // Prix du cours
            $table->string('price_stripe')->nullable(); // Produit du cours dans stripe
            $table->string('category'); // Catégorie du cours
            $table->integer('total_videos'); // Nombre total de vidéos
            $table->text('description'); // Description du cours
            $table->string('cover', 2048); // Chemin vers l'image de couverture
            $table->string('video'); // Ajout de la colonne vidéo
            $table->timestamps(); // Timestamps created_at et updated_at
            $table->text('short_description'); // Courte description du cours
            $table->integer('students')->default(0); // Nombre d'étudiants inscrits

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
