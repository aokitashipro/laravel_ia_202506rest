<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController; //追記
use App\Http\Controllers\Api\BookController; //追記
use App\Http\Controllers\Api\MailController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/products', ProductController::class); //追記

Route::apiResource('/books', BookController::class);

Route::get('/mail-test', [ MailController::class, 'index']); //追記
