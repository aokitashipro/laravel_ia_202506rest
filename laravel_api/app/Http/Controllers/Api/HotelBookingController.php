<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Http\Resources\HotelBookingResource;
use App\Http\Resources\HotelBookingListResource;
use App\Http\Requests\HotelBookingRequest;

use Illuminate\Http\Request;

class HotelBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = HotelBooking::orderBy('checkin_date')->paginate(10);
        return HotelBookingListResource::collection($bookings);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HotelBookingRequest $request)
    {
            $booking = HotelBooking::create($request->validated());
            return new HotelBookingResource($booking);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hotelBooking = HotelBooking::findOrFail($id);
        return new HotelBookingResource($hotelBooking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // 9-1
    public function upcomingBookings()
    {
        // dd('test');
        // return response()->json(
        //     ['hello world!'], 200
        // );

        $bookings = HotelBooking::upcoming()->orderBy('checkin_date')->get();
        return HotelBookingListResource::collection($bookings);
    }
}
