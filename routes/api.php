<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware('lang')->group(function () {
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::get('email-verify',[AuthController::class,'verifyEmail']);
    Route::post('logincode',[AuthController::class,'LoginCode']);
    Route::post('enterbyPhone',[AuthController::class,'entercode']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout',[AuthController::class,'logout']);
    });
});