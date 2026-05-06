<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

// Your existing routes...

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // FUND WALLET ROUTES
    Route::get('/fund-wallet', [PaymentController::class, 'showFundForm'])->name('fund.wallet');
    Route::post('/fund-wallet', [PaymentController::class, 'initialize'])->name('fund.wallet.post');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
});

