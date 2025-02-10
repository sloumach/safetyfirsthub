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
        Schema::table('courses', function (Blueprint $table) {
            $table->text('short_description')->nullable()->after('description'); // Courte description du cours
            $table->integer('students')->default(0)->after('short_description'); // Nombre d'étudiants inscrits
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['short_description', 'students']);
        });
    }
};
