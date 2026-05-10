<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add-balance-column', function () {
    try {
        DB::statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS wallet_balance DECIMAL(10,2) DEFAULT 0');
        return 'SUCCESS: wallet_balance column added. Now go to /force-credit';
    } catch (Exception $e) {
        return 'ERROR: ' . $e->getMessage();
    }
});

Route::get('/force-credit', function () {
    $user = auth()->user();
    if (!$user) {
        return 'Please login first';
    }
    $user->wallet_balance = 600;
    $user->save();
    return 'Wallet Balance: ₦' . number_format($user->wallet_balance, 2);
});
