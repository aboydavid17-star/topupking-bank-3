<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

session_start();

// TEMP FIX TRANSACTIONS - DELETE AFTER USE
Route::get('/fix-db-transactions', function () {
    try {
        DB::statement('ALTER TABLE transactions ADD COLUMN IF NOT EXISTS description VARCHAR(255) NULL');
        DB::statement('ALTER TABLE transactions ADD COLUMN IF NOT EXISTS type VARCHAR(50) NULL');
        DB::statement('ALTER TABLE transactions ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT \'pending\'');
        DB::statement('ALTER TABLE transactions ADD COLUMN IF NOT EXISTS reference VARCHAR(255) NULL');
        DB::statement('ALTER TABLE transactions ADD COLUMN IF NOT EXISTS amount DECIMAL(10,2) DEFAULT 0');
        return '<h1 style="color:green;">SUCCESS: Transactions table fixed!</h1><p>Now DELETE the /fix-db-transactions route and redeploy.</p>';
    } catch (Exception $e) {
        return '<h1 style="color:red;">Error:</h1> ' . $e->getMessage();
    }
});

function authCheck() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    return DB::table('users')->find($_SESSION['user_id']);
}

Route::get('/', function () {
    return '<!DOCTYPE html><html><head><title>TopupKing</title><meta name="viewport" content="width=device-width,initial-scale=1"></head><body style="font-family:Arial;text-align:center;padding:50px;background:#f4f4f4;"><h1>Welcome to TopupKing</h1><p>Fast & Reliable VTU Services</p><a href="/register" style="padding:15px 30px;background:#007bff;color:white;text-decoration:none;border-radius:5px;margin:10px;display:inline-block;">Sign Up</a><a href="/login" style="padding:15px 30px;background:#28a745;color:white;text-decoration:none;border-radius:5px;margin:10px;display:inline-block;">Login</a></body></html>';
});

Route::get('/register', function () {
    $error = session('error') ? '<div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin-bottom:20px;">'.session('error').'</div>' : '';
    $token = csrf_token();
    return '<!DOCTYPE html><html><head><title>Register</title><meta name="viewport" content="width=device-width,initial-scale=1"></head><body style="font-family:Arial;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;"><div style="max-width:400px;margin:30px auto;background:white;padding:30px;border-radius:15px;"><h2 style="text-align:center;">Create Account</h2>'.$error.'<form method="POST" action="/register"><input type="hidden" name="_token" value="'.$token.'"><input name="name" required placeholder="Full Name" style="padding:12px;width:100%;margin-bottom:15px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;"><input name="email" type="email" required placeholder="Email" style="padding:12px;width:100%;margin-bottom:15px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;"><input name="phone" required placeholder="Phone 08012345678" maxlength="11" style="padding:12px;width:100%;margin-bottom:15px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;"><input name="password" type="password" required placeholder="Password" style="padding:12px;width:100%;margin-bottom:20px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;"><button style="padding:15px;width:100%;background:#28a745;color:white;border:none;border-radius:8px;font-size:16px;">Register</button></form><p style="text-align:center;margin-top:15px;">Have account? <a href="/login">Login</a></p></div></body></html>';
});

Route::post('/register', function () {
    $exists = DB::table('users')->where('email', request('email'))->orWhere('phone', request('phone'))->first();
    if ($exists) return back()->with('error', 'Email or phone already exists');
    
    $userId = DB::table('users')->insertGetId([
        'name' => request('name'),
        'email' => request('email'),
        'phone' => request('phone'),
        'password' => Hash::make(request('password')),
        'wallet_balance' => 0
    ]);
    
    $_SESSION['user_id'] = $userId;
    return redirect('/dashboard');
});

Route::get('/login', function () {
    $error = session('error') ? '<div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin-bottom:20px;">'.session('error').'</div>' : '';
    $token = csrf_token();
    return '<!DOCTYPE html><html><head><title>Login</title><meta name="viewport" content="width=device-width,initial-scale=1"></head><body style="font-family:Arial;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;"><div style="max-width:400px;margin:30px auto;background:white;padding:30px;border-radius:15px;"><h2 style="text-align:center;">Login</h2>'.$error.'<form method="POST"
