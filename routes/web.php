<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});

// GUEST ROUTES - only if not logged in
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// AUTH ROUTES - only if logged in
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Fund Wallet - FIXED: changed 'inde' to 'index'
    Route::get('/fund-wallet', [WalletController::class, 'index'])->name('fund-wallet');
    Route::post('/fund-wallet', [WalletController::class, 'store'])->name('fund-wallet.store');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
