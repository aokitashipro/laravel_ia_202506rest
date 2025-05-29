<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController; 
use App\Http\Controllers\Api\BookController; 
use App\Http\Controllers\Api\SportingGoodController; 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/products', ProductController::class); //追記

Route::apiResource('/books', BookController::class);

Route::apiResource('/sporting-goods', SportingGoodController::class);