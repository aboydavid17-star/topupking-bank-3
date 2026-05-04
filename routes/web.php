<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AirtimeController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Wallet Funding
    Route::get('/wallet/fund', [WalletController::class, 'showFundForm'])->name('wallet.fund');
    Route::post('/wallet/fund', [WalletController::class, 'initializePayment'])->name('wallet.fund.init');
    Route::get('/wallet/callback', [WalletController::class, 'handleCallback'])->name('wallet.callback');
    
    // Airtime
    Route::get('/airtime', [AirtimeController::class, 'index'])->name('airtime.index');
    Route::post('/airtime/buy', [AirtimeController::class, 'buy'])->name('airtime.buy');
    
    // Data
    Route::get('/data', [DataController::class, 'index'])->name('data.index');
    Route::post('/data/buy', [DataController::class, 'buy'])->name('data.buy');
});

// NO require auth.php LINE HERE - DELETE AM
