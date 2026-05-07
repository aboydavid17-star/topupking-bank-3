<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');

// Dashboard - add your dashboard route here if you have one
Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware('auth')->name('dashboard');

// Wallet Routes
Route::middleware('auth')->group(function () {
    Route::get('/fund-wallet', [WalletController::class, 'index'])->name('fund-wallet.index');
    Route::post('/fund-wallet', [WalletController::class, 'store'])->name('fund-wallet.store');
});
