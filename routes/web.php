<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;

Route::get('/', function () {
    return view('welcome');
});

// Wallet route - WITH AUTH NOW
Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
});

// Login routes - Laravel default
Route::get('/login', function() {
    return "Login page here. Use /register to create account";
})->name('login');
