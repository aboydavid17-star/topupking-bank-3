<?php

use Illuminate\Support\Facades\Route;
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

// Wallet Routes - Protected by auth
Route::middleware(['auth'])->group(function () {
    
    // Wallet Dashboard
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    
    // Fund Wallet
    Route::get('/fund-wallet', [WalletController::class, 'fundWalletPage'])->name('wallet.fund');
    Route::post('/fund-wallet', [WalletController::class, 'fundWallet'])->name('wallet.fund.post');
    
    // Buy Data
    Route::get('/buy-data', [WalletController::class, 'buyDataPage'])->name('wallet.buy-data');
    Route::post('/buy-data', [WalletController::class, 'buyData'])->name('wallet.buy-data.post');
    
});
