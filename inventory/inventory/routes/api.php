<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register',[UserController::class,'userRegistration']);
Route::post('/login',[UserController::class,'UserLogin']);
Route::post('/send-otp',[UserController::class,'EmailVerify']);
Route::post('/verify-otp',[UserController::class,'VerifyOTP']);

Route::post('/resetPassword',[UserController::class,'ResetPassword'])->middleware( TokenVerificationMiddleware::class);
