<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Wallet Routes
Route::get('/fund-wallet', [WalletController::class, 'showFundForm'])->middleware('auth');
Route::post('/fund-wallet', [WalletController::class, 'fund'])->middleware('auth');
Route::get('/payment/callback', [WalletController::class, 'handleCallback']);
