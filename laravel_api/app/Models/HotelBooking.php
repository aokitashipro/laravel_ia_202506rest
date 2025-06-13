<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;


class HotelBooking extends Model
{
    /** @use HasFactory<\Database\Factories\HotelBookingFactory> */
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

    // 9-2. パラメータ付きスコープ
    #[Scope]
    protected function ofRoomType(Builder $query, ?string $roomType = null): void
    {
        if ($roomType) {
            $query->where('room_type', $roomType);
        }
    }

    // 9-3.
    #[Scope]
    protected function longStay(Builder $query, int $minNights = 7): void
    {
        $query->whereRaw('DATEDIFF(checkout_date, checkin_date) >= ?', [$minNights]);
    }

    // 問題4: 現在滞在中
    #[Scope]
    protected function currentStay(Builder $query): void
    {
        $query->where('checkin_date', '<=', now())
            ->where('checkout_date', '>', now());
    }

    //9-5. 
    #[Scope]
    protected function betweenDates(Builder $query, ?string $startDate = null, ?string $endDate = null): void
    {
        if ($startDate) {
            $query->where('checkin_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('checkin_date', '<=', $endDate);
        }
    }

    // 9-6
    #[Scope]
    protected function guestCountBetween(Builder $query, ?int $minGuests = null, ?int $maxGuests = null): void
    {
        if ($minGuests) {
            $query->where('guest_count', '>=', $minGuests);
        }
        if ($maxGuests) {
            $query->where('guest_count', '<=', $maxGuests);
        }
    }

    #[Scope]
    protected function getPremiumBookings(Builder $query)
    {
        $query->upcoming()
                ->ofRoomType('スイート')
                ->longStay(10)
                ->guestCountBetween(3, 6)
                ->orderBy('checkin_date');
                
}

}
