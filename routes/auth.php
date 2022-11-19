<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResendCodeVerificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (){
    Route::post('/register',[RegisterController::class,'__invoke']);
    Route::post('/login',[LoginController::class,'__invoke']);
});

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/resendCodeVerification',[ResendCodeVerificationController::class,'__invoke']);
    Route::post('/verifyEmail', [VerifyEmailController::class,'__invoke'])
        ->middleware(['throttle:6,1']);
    Route::get('/logout', [LogoutController::class,'__invoke']);

});

