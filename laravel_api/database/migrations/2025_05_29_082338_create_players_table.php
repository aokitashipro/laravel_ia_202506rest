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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // teams テーブルとの関連付けをするための外部キーを設定する 
            $table->foreignId('team_id') // team_id というカラムを作成する
                ->nullable() // 外部キーにnull を設定できるようにする
                ->constrained('teams');  // 関連づけるテーブル名を指定する(※先にteams テーブルを作成しておく必要がある)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
