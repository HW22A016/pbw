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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();

            // usersテーブルと紐づけ
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 初期値
            $table->integer('level')->default(1);
            $table->integer('exp')->default(0);
            $table->integer('max_hp')->default(100);
            $table->integer('hp')->default(100);
            $table->integer('atk')->default(10);
            $table->integer('money')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
