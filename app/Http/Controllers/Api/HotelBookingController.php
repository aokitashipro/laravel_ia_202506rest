<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelBookingListResource;
use App\Models\HotelBooking;
use Illuminate\Http\Request;

class HotelBookingController extends Controller
{
    public function upcomingBookings()
    {
        $bookings = HotelBooking::upcoming()->orderBy('checkin_date')->get();
        return HotelBookingListResource::collection($bookings);
    }
} 