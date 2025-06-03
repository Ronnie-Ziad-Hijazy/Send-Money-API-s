<?php

use App\Http\Controllers\Mobile\API\V1\AuthUserController;
use App\Http\Controllers\Mobile\API\V1\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// User Authentication
Route::post('/login' , [AuthUserController::class, 'login']);
Route::post('/register' , [AuthUserController::class, 'register']);

// Transaction
Route::middleware(['auth' , 'user'])->group(function () {
    // Is Logged In
    Route::post('/is-valid' , [AuthUserController::class, 'isValid']);
    Route::post('/logout' , [AuthUserController::class, 'logout']);

    // RateLimit 5 requests per minute
    Route::get('/get-balance' , [TransactionController::class, 'getWalletBalance']);
    Route::post('/send-money' , [TransactionController::class, 'sendMoney'])->middleware(['ratelimit:send_money_,5,60']);
});