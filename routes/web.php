<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('wallet.index');
});

Route::middleware(['auth'])->group(function () {
    
    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/fund', [WalletController::class, 'fund'])->name('fund');
    Route::get('/paystack/callback', [WalletController::class, 'handleGatewayCallback'])->name('paystack.callback');
    
    // Transaction Routes
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    
    // Profile Routes - Breeze default
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
