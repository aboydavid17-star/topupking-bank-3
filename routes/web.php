<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// Wallet Routes
Route::get('/fund-wallet', [WalletController::class, 'showFundForm'])->middleware('auth');
Route::post('/fund-wallet', [WalletController::class, 'fund'])->middleware('auth');
Route::get('/payment/callback', [WalletController::class, 'handleCallback']);
