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
        Schema::create('section_quiz_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('section_quiz_questions')->onDelete('cascade');
            $table->text('choice_text'); // Texte du choix
            $table->boolean('is_correct')->default(false); // Indique si c'est la bonne réponse
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('section_quiz_choices');
    }
};
