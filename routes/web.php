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

// Auth routes - Laravel Breeze/Fortify dey handle this
require __DIR__.'/auth.php';

// Protected routes - user must login first
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/fund', [WalletController::class, 'create'])->name('wallet.create');
    Route::post('/wallet/fund', [WalletController::class, 'store'])->name('wallet.store');
    Route::get('/wallet/verify/{reference}', [WalletController::class, 'verify'])->name('wallet.verify');
    
});
