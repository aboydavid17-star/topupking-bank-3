<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;

Route::get('/', function () {
    return redirect('/wallet');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/fund', [WalletController::class, 'showFundingForm'])->name('wallet.fund');
    Route::post('/wallet/fund', [WalletController::class, 'fund'])->name('fund');
    Route::get('/wallet/verify', [WalletController::class, 'verifyPayment'])->name('wallet.verify');
    Route::get('/force-credit', [WalletController::class, 'forceCredit'])->name('wallet.force');
});

require __DIR__.'/auth.php';
