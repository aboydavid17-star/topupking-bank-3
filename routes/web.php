<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Clear cache route
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "Cache cleared successfully";
});

// Login routes - basic version if you don't have Breeze
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Basic login logic
    $credentials = request()->only('email', 'password');
    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();
        return redirect()->intended('dashboard');
    }
    return back()->withErrors(['email' => 'Invalid credentials']);
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/fund-wallet', [WalletController::class, 'showFundForm'])->name('fund.wallet');
    Route::post('/fund-wallet', [WalletController::class, 'initializePayment'])->name('fund.wallet.post');
    Route::get('/payment/callback', [WalletController::class, 'paymentCallback'])->name('payment.callback');
});
