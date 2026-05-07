<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/fund', [WalletController::class, 'fund'])->name('wallet.fund');
    Route::get('/wallet/callback', [WalletController::class, 'callback'])->name('wallet.callback');
});

require __DIR__.'/auth.php';
