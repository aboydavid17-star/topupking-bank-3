<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| TEMP MIGRATION ROUTE - DELETE AFTER USE
|--------------------------------------------------------------------------
*/
Route::get('/boss-run-migrate', function() {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        return "<h1>MIGRATION RESULT:</h1><pre>$output</pre>";
    } catch (Exception $e) {
        return "<h1>ERROR:</h1><pre>" . $e->getMessage() . "</pre>";
    }
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Protected Routes
Route::middleware('auth')->group(function () {
    
    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/fund', [WalletController::class, 'showFundForm'])->name('wallet.fund.form');
    Route::post('/wallet/fund', [WalletController::class, 'fund'])->name('wallet.fund');
    
    // Data Routes
    Route::get('/data', [DataController::class, 'index'])->name('data.index');
    Route::post('/data/buy', [DataController::class, 'buy'])->name('data.buy');
    
});
