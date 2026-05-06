<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Fund Wallet Routes - THIS FIXES YOUR 404 👇
Route::get('/fund-wallet', [WalletController::class, 'showFundForm'])
    ->middleware('auth')
    ->name('fund-wallet');

Route::post('/fund-wallet', [WalletController::class, 'initializePayment'])
    ->middleware('auth')
    ->name('fund-wallet.pay');

Route::get('/fund-wallet/callback', [WalletController::class, 'handleCallback'])
    ->middleware('auth')
    ->name('fund-wallet.callback');
