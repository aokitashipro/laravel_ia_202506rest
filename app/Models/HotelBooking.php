<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class HotelBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_name', 
        'email', 
        'phone', 
        'checkin_date', 
        'checkout_date', 
        'guest_count', 
        'room_type', 
        'special_requests'
    ];

    protected $casts = [
        'checkin_date' => 'date',
        'checkout_date' => 'date',
    ];

    // 9-1. 今後の予約一覧を取得する機能
    #[Scope]
    protected function upcoming(Builder $query): void
    {
        $query->where('checkin_date', '>=', now());
    }
} 