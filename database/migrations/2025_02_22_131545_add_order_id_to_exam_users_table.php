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
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('exam_users', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }
};
