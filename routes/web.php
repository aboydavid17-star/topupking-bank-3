<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

// Manual Auth Routes - No laravel/ui needed
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Wallet Routes - Protected by auth
Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::get('/fund-wallet', [WalletController::class, 'fundWalletPage'])->name('wallet.fund');
    Route::post('/fund-wallet', [WalletController::class, 'fundWallet'])->name('wallet.fund.post');
    Route::get('/buy-data', [WalletController::class, 'buyDataPage'])->name('wallet.buy-data');
    Route::post('/buy-data', [WalletController::class, 'buyData'])->name('wallet.buy-data.post');
});
