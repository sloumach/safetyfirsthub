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
        Schema::create('section_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->integer('passing_score')->default(70); // Score minimum pour réussir
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('section_quizzes');
    }
};
