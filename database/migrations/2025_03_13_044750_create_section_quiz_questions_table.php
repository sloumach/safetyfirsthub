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
        Schema::create('section_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('section_quizzes')->onDelete('cascade');
            $table->text('question_text'); // Texte de la question
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('section_quiz_questions');
    }
};
