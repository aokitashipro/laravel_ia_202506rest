<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController; 
use App\Http\Controllers\Api\BookController; 
use App\Http\Controllers\Api\CafeController;
use App\Http\Controllers\Api\SportingGoodController; 
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\PlayerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/products', ProductController::class); //追記

Route::apiResource('/books', BookController::class);

Route::apiResource('/sporting-goods', SportingGoodController::class);

Route::apiResource('/teams',   TeamController::class)->only(['index', 'show']);
Route::apiResource('/players', PlayerController::class)->only(['index', 'store', 'show', 'update']);

Route::apiResource('/cafes', CafeController::class);