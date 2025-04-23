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
        Schema::table('section_quizzes', function (Blueprint $table) {
            $table->string('title')->after('section_id'); // ou after('id') si tu préfères
        });
    }

    public function down()
    {
        Schema::table('section_quizzes', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }

};
