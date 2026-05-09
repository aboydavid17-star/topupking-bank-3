<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;

// Test route
Route::get('/ping', fn() => 'TOPUPKING IS ALIVE - ' . config('app.url'));

// Root route - NO AUTH MIDDLEWARE HERE
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('wallet');
    }
    return redirect()->route('login');
});

// Auth protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::post('/fund', [WalletController::class, 'fund'])->name('fund.wallet');
});

// THIS LINE IS CRITICAL - DO NOT DELETE
require __DIR__.'/auth.php';
