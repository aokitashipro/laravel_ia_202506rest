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
        Schema::create('sporting_goods', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // 商品名
            $table->string('category');                  // カテゴリ（例：バット、ラケットなど）
            $table->string('brand');                     // ブランド名
            $table->integer('price');                    // 価格（整数）
            $table->float('weight')->nullable();         // 重量（kg）
            $table->boolean('is_available');             // 販売中かどうか
            $table->integer('stock');                    // 在庫数
            $table->date('release_date');                // 発売日
            $table->string('color')->nullable();         // カラー
            $table->string('sku')->unique();             // 商品コード
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sporting_goods');
    }
};
