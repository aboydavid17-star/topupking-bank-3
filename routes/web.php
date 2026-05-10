<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/fund', [WalletController::class, 'showFundForm'])->name('wallet.fund');
    Route::post('/wallet/fund', [WalletController::class, 'initializePayment'])->name('wallet.initialize');
    Route::get('/wallet/verify', [WalletController::class, 'verifyPayment'])->name('wallet.verify');
    
    // Temp route to force credit - DELETE AFTER TESTING
    Route::get('/force-credit', function() {
        $user = Auth::user();
        $wallet = $user->wallet()->firstOrCreate(['user_id' => $user->id]);
        $wallet->increment('balance', 600);
        
        $user->transactions()->create([
            'type' => 'credit',
            'amount' => 600,
            'description' => 'Manual Credit - Paystack Test',
            'reference' => 'MANUAL_' . time(),
            'status' => 'success'
        ]);
        
        return redirect('/wallet')->with('success', '₦600 credited successfully');
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
