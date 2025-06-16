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

    // 9-2.
    public function filterByRoomType(Request $request)
    {
        $request->validate([
            'room_type' => 'nullable|in:シングル,ダブル,ツイン,スイート,ファミリー',
        ]);

        $bookings = HotelBooking::ofRoomType($request->room_type)->get();
        return HotelBookingListResource::collection($bookings);
    }

    // 9-3.
    public function longStayBookings(Request $request)
    {
        $request->validate([
            'min_nights' => 'nullable|integer|min:1|max:30'
        ]);

        $minNights = $request->min_nights ?? 7;
        $bookings = HotelBooking::longStay($minNights)->upcoming()->get();
        return HotelBookingListResource::collection($bookings);
    }

    // 9-4.
    public function currentGuests()
    {
        $bookings = HotelBooking::currentStay()->get();;
        return HotelBookingListResource::collection($bookings);
    }

    // 9-5.
    public function searchByPeriod(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $bookings = HotelBooking::betweenDates(
            $request->startDate, 
            $request->endDate
        )->get();
        return HotelBookingListResource::collection($bookings);
    }

    // 9-6.
    public function filterByGuestCount(Request $request)
    {
        $request->validate([
            'min_guests' => 'nullable|integer|min:1',
            'max_guests' => 'nullable|integer|max:10|gte:min_guests'
        ]);

        $bookings = HotelBooking::guestCountBetween(
            $request->min_guests, 
            $request->max_guests
        )->orderBy('checkin_date')->get();
        return HotelBookingListResource::collection($bookings);
    }

    //9-7
    public function premiumSearch()
    {
        $bookings = HotelBooking::getPremiumBookings()->get();
        return HotelBookingListResource::collection($bookings);
    }
    
}
