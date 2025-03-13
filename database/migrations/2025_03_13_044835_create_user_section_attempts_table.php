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
        Schema::create('user_section_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->integer('score')->nullable(); // Score obtenu
            $table->enum('status', ['passed', 'failed'])->nullable(); // Statut de la tentative
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_section_attempts');
    }
};
