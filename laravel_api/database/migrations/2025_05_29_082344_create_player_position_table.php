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
        Schema::create('player_position', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id'); // players テーブルのidを指定するカラム
            $table->foreignId('position_id'); // positions テーブルのidを指定するカラム
            $table->unsignedTinyInteger('skill')->default(1); // 1〜100 で熟練度
            $table->date('assigned_from')->nullable();       // 何年からこのポジション？
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_position');
    }
};
