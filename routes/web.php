<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Wallet Routes
Route::get('/fund-wallet', [WalletController::class, 'showFundForm'])->middleware('auth');
Route::post('/fund-wallet', [WalletController::class, 'fund'])->middleware('auth');
Route::get('/payment/callback', [WalletController::class, 'handleCallback']);

// TEMPORARY FIX - DELETE AFTER RUNNING ONCE
Route::get('/fix-topupking-db', function () {
    DB::statement("ALTER TABLE transactions ALTER COLUMN network DROP NOT NULL");
    DB::statement("ALTER TABLE transactions ALTER COLUMN phone_number DROP NOT NULL"); 
    DB::statement("ALTER TABLE transactions ALTER COLUMN plan_name DROP NOT NULL");
    return "<h1 style='color:green;font-family:Arial;padding:50px;text-align:center;'>DONE: Database fixed. Fund Wallet works now. DELETE THIS ROUTE 👑</h1>";
});
