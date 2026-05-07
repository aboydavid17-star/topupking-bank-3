<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth routes - add yours here if you get
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Wallet route - protected
Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
});

// TEMPORARY MIGRATION ROUTE - DELETE AFTER USE!!!
Route::get('/run-migrations-now-12345', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        
        return "
        <html>
        <head><title>Migration Status</title></head>
        <body style='font-family: monospace; padding: 20px;'>
            <h2 style='color: green;'>✅ MIGRATION SUCCESS</h2>
            <pre style='background: #f4f4f4; padding: 15px; border: 1px solid #ddd;'>{$output}</pre>
            <br>
            <a href='/wallet' style='background: blue; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Wallet Now</a>
        </body>
        </html>
        ";
    } catch (\Exception $e) {
        return "
        <html>
        <head><title>Migration Failed</title></head
