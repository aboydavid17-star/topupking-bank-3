<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| TopupKing Routes - ALL BUTTONS WORKING
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
    
    // Dashboard - blade files call route('wallet')
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    
    // Fund Wallet - blade files call route('fund.wallet') 
    Route::get('/wallet/fund', [WalletController::class, 'showFund'])->name('fund.wallet');
    Route::post('/wallet/fund', [WalletController::class, 'fundWallet'])->name('fund.wallet.post');
    
    // Buy Data - blade files call route('buy.data')
    Route::get('/wallet/buy-data', [WalletController::class, 'showBuyData'])->name('buy.data');
    Route::post('/wallet/buy-data', [WalletController::class, 'buyData'])->name('buy.data.post');
    
    // Buy Airtime - blade files call route('buy.airtime')
    Route::get('/wallet/buy-airtime', [WalletController::class, 'showBuyAirtime'])->name('buy.airtime');
    Route::post('/wallet/buy-airtime', [WalletController::class, 'buyAirtime'])->name('buy.airtime.post');
    
    // Cable TV - blade files call route('cable')
    Route::get('/wallet/cable', [WalletController::class, 'showCable'])->name('cable');
    Route::post('/wallet/cable', [WalletController::class, 'buyCable'])->name('cable.post');
    
    // Transaction History
    Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('transactions');
});
