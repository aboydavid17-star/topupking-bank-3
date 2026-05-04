<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AirtimeController; // <-- NEW FOR AIRTIME

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth routes from Laravel Breeze/Jetstream
require __DIR__.'/auth.php';

// Protected Routes - User must login first
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/fund', [WalletController::class, 'fund'])->name('wallet.fund');
    Route::get('/wallet/verify/{reference}', [WalletController::class, 'verify'])->name('wallet.verify');
    
    // ===== AIRTIME ROUTES - NEW =====
    Route::get('/airtime', [AirtimeController::class, 'index'])->name('airtime.index');
    Route::post('/airtime/buy', [AirtimeController::class, 'buy'])->name('airtime.buy');
    // ================================
    
});
