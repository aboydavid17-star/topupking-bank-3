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
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Route::get('/add-wallet-column', function () {
    if (!Schema::hasColumn('users', 'wallet_balance')) {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('email');
        });
        return 'Column wallet_balance added successfully! Now delete this route.';
    }
    return 'Column already exists.';
});
