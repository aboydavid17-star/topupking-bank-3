<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\WalletController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "Cache cleared successfully";
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/fund-wallet', [WalletController::class, 'showFundForm'])->name('fund.wallet');
    Route::post('/fund-wallet', [WalletController::class, 'initializePayment'])->name('fund.wallet.post');
    Route::get('/payment/callback', [WalletController::class, 'paymentCallback'])->name('payment.callback');
});
