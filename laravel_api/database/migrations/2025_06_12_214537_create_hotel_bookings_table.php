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
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
             
            $table->string('guest_name', 100);
            $table->string('email');
            $table->string('phone', 20);
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('guest_count');
            $table->enum('room_type', ['シングル', 'ダブル', 'ツイン', 'スイート', 'ファミリー']);
            $table->text('special_requests')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};
