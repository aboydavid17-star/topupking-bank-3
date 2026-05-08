<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Artisan;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Auth protected routes
Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::get('/buy-data', [WalletController::class, 'buyDataPage'])->name('buy.data');
    Route::post('/buy-data', [WalletController::class, 'buyData']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Temp route to run migration - DELETE AFTER USE
Route::get('/run-migrations', function () {
    Artisan::call('migrate', ['--force' => true]);
    return "Migrations done!";
});

// Temp route for cache clearing - DELETE AFTER USE  
Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return 'Cache cleared!';
});
