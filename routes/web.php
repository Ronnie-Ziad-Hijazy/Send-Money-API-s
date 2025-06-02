<?php

use App\Http\Controllers\MailController;
use App\Mail\MoneySentMail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/preview-email', function () {
//     $transaction = \App\Models\Transaction::latest()->with(['sender', 'recipient'])->first();

//     return new MoneySentMail($transaction);
// });
Route::post('/test-notify', [MailController::class, 'contactUs']);