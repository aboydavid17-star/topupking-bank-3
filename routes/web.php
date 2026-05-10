<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fix-and-fund', function () {
    try {
        // Step 1: Add column if it doesn't exist
        DB::statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS wallet_balance DECIMAL(10,2) DEFAULT 0');
        
        // Step 2: Credit the user
        $user = auth()->user();
        if (!$user) {
            return 'ERROR: Please login first, then refresh this page';
        }
        
        DB::table('users')->where('id', $user->id)->update(['wallet_balance' => 600]);
        
        $newBalance = DB::table('users')->where('id', $user->id)->value('wallet_balance');
        
        return 'DONE BOSS ✅<br>Wallet Balance: ₦' . number_format($newBalance, 2);
        
    } catch (Exception $e) {
        return 'REAL ERROR: ' . $e->getMessage();
    }
});
