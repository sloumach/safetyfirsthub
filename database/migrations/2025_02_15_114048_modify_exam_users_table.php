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
        Schema::table('exam_users', function (Blueprint $table) {
            $table->json('user_answers')->nullable()->after('status'); // Stocker les réponses sous JSON
        });
    }

    public function down(): void
    {
        Schema::table('exam_users', function (Blueprint $table) {
            $table->dropColumn('user_answers');
        });
    }
};
