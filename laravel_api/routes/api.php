<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController; //追記
use App\Http\Controllers\Api\BookController; //追記
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\MailController; //追記
use App\Http\Controllers\Api\CafeController;
use App\Http\Controllers\Api\SportingGoodController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\HotelBookingController;

Route::post('/register', [AuthController::class, 'register']);   // ★任意
Route::post('/login',    [AuthController::class, 'login'])->name('api.login');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::apiResource('/products', ProductController::class); //追記

Route::apiResource('/books', BookController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/products', ProductController::class); //追記
});

Route::apiResource('/sporting-goods', SportingGoodController::class);

Route::apiResource('/teams',   TeamController::class)->only(['index', 'show']);
Route::apiResource('/players', PlayerController::class)->only(['index', 'store', 'show', 'update']);

Route::apiResource('/cafes', CafeController::class);

Route::get('/mail-test', [ MailController::class, 'index']); //追記

Route::get('hotel-bookings/upcoming', [HotelBookingController::class, 'upcomingBookings']);
Route::get('hotel-bookings/filter/room-type', [HotelBookingController::class, 'filterByRoomType']);
Route::get('hotel-bookings/long-stay', [HotelBookingController::class, 'longStayBookings']);
Route::get('hotel-bookings/current-guests', [HotelBookingController::class, 'currentGuests']);
Route::get('hotel-bookings/search/period', [HotelBookingController::class, 'searchByPeriod']);
Route::get('hotel-bookings/filter/guest-count', [HotelBookingController::class, 'filterByGuestCount']);
Route::get('hotel-bookings/premium-search', [HotelBookingController::class, 'premiumSearch']);


Route::apiResource('hotel-bookings', HotelBookingController::class);
