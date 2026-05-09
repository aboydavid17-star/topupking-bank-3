<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| TopupKing Web Routes - BUG #14 KILLED
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected Routes - Must be logged in
Route::middleware(['auth'])->group(function () {
    
    // Wallet Dashboard - RENAMED TO 'wallet' TO FIX BUTTONS
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    
    // Fund Wallet
    Route::get('/wallet/fund', [WalletController::class, 'showFund'])->name('wallet.fund');
    Route::post('/wallet/fund', [WalletController::class, 'fundWallet'])->name('wallet.fund.post');
    
    // Buy Data
    Route::get('/wallet/buy-data', [WalletController::class, 'showBuyData'])->name('wallet.buy-data');
    Route::post('/wallet/buy-data', [WalletController::class, 'buyData'])->name('wallet.buy-data.post');
    
    // Buy Airtime
    Route::get('/wallet/buy-airtime', [WalletController::class, 'showBuyAirtime'])->name('wallet.buy-airtime');
    Route::post('/wallet/buy-airtime', [WalletController::class, 'buyAirtime'])->name('wallet.buy-airtime.post');
    
    // Cable TV
    Route::get('/wallet/cable', [WalletController::class, 'showCable'])->name('wallet.cable');
    Route::post('/wallet/cable', [WalletController::class, 'buyCable'])->name('wallet.cable.post');
    
    // Transaction History
    Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');
});
