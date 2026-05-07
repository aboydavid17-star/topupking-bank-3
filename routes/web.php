<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

// Auth Routes - Guest only
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Wallet - Protected, must login first
Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
});

// TEMP MIGRATION ROUTE - DELETE AFTER USE
Route::get('/run-migrations-now-12345', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        return "<html><body style='font-family: monospace; padding: 20px;'><h2 style='color: green;'>✅ MIGRATION SUCCESS</h2><pre>{$output}</pre><br><a href='/register'>Create Account Now</a></body></html>";
    } catch (\Exception $e) {
        return "<html><body style='font-family: monospace; padding: 20px;'><h2 style='color: red;'>❌ FAILED</h2><pre>". $e->getMessage() ."</pre></body></html>";
    }
});
