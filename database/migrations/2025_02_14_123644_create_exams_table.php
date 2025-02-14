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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete(); // Lien avec un cours
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration')->default(30); // Durée de l'examen en minutes
            $table->integer('passing_score')->default(70); // Score minimum pour réussir
            $table->boolean('is_active')->default(true); // Activation/Désactivation de l'examen
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
