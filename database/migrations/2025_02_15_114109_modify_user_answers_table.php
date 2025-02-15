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
        Schema::table('user_answers', function (Blueprint $table) {
            $table->index('exam_id'); // Ajout d'un index pour accélérer les requêtes sur exam_id
            $table->index('question_id'); // Optimisation des recherches sur question_id
            $table->boolean('is_correct')->nullable()->change(); // Permettre des valeurs nulles pour éviter les erreurs
        });
    }

    public function down(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropIndex(['exam_id']);
            $table->dropIndex(['question_id']);
            $table->boolean('is_correct')->change();
        });
    }
};
