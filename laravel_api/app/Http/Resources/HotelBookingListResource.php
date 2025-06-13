<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelBookingListResource extends JsonResource
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
            'checkin_date' => $this->checkin_date->format('n/j'),
            'checkout_date' => $this->checkout_date->format('n/j'),
            'nights' => $this->checkin_date->diffInDays($this->checkout_date) . '泊',
            'room_type' => $this->room_type,
            'guest_count' => $this->guest_count . '名',
        ];    }
}
