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
        // 🔹 Modifier la table existante `video_progress`
        Schema::table('video_progress', function (Blueprint $table) {
            // Supprimer la relation avec `course_id`
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');

            // Ajouter la relation avec `video_id`
            $table->foreignId('video_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('video_progress', function (Blueprint $table) {
            // Rétablir l'ancienne colonne `course_id`
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();

            // Supprimer la colonne `video_id`
            $table->dropForeign(['video_id']);
            $table->dropColumn('video_id');
        });
    }
};
