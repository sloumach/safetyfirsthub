<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('coupon_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->integer('times_used')->default(1); // Nombre de fois que l'user a utilisé ce coupon
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('coupon_user');
    }
};
