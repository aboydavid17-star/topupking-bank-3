<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Health check route - test if app is alive
Route::get('/ping', fn() => 'TOPUPKING IS ALIVE - ' . config('app.url'));

// Root route - send to wallet if logged in, else login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('wallet');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Protected Routes - Must be logged in
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Wallet Dashboard
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    
    // Fund Wallet - THIS FIXES YOUR 500 ERROR
    Route::get('/fund', [WalletController::class, 'showFundForm'])->name('wallet.fund');
    Route::post('/fund', [WalletController::class, 'fund'])->name('wallet.fund.store');
    
    // Buy Data - add later
    Route::get('/buy-data', [WalletController::class, 'showDataForm'])->name('wallet.data');
    Route::post('/buy-data', [WalletController::class, 'buyData'])->name('wallet.data.store');
    
    // Buy Airtime - add later  
    Route::get('/buy-airtime', [WalletController::class, 'showAirtimeForm'])->name('wallet.airtime');
    Route::post('/buy-airtime', [WalletController::class, 'buyAirtime'])->name('wallet.airtime.store');
});

/*
|--------------------------------------------------------------------------
| Auth Routes - Login, Register, Logout
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
