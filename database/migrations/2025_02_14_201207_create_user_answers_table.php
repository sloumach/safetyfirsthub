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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('choice_id')->constrained()->cascadeOnDelete()->nullable(); // Réponse sélectionnée
            $table->boolean('is_correct'); // Vérifie si la réponse est correcte
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
