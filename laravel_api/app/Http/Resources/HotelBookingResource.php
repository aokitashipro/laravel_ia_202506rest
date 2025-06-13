<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [ 
        'id' => $this->id,
            'guest_name' => $this->guest_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'checkin_date' => $this->checkin_date->format('Y年n月j日'),
            'checkout_date' => $this->checkout_date->format('Y年n月j日'),
            'stay_duration' => $this->checkin_date->diffInDays($this->checkout_date) . '泊',
            'guest_count' => $this->guest_count . '名',
            'room_type' => $this->room_type,
            'special_requests' => $this->special_requests ?? '特になし',
            'total_nights' => $this->checkin_date->diffInDays($this->checkout_date),
            'checkin_day_of_week' => $this->checkin_date->locale('ja')->dayName,
            'checkout_day_of_week' => $this->checkout_date->locale('ja')->dayName,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
