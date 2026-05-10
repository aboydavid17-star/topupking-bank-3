<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Dashboard - passes real balance
Route::get('/home', function () {
    $balance = DB::table('users')->where('id', auth()->id())->value('wallet_balance');
    return view('dashboard', ['balance' => $balance]);
})->middleware('auth')->name('home');

Route::get('/dashboard', function () {
    $balance = DB::table('users')->where('id', auth()->id())->value('wallet_balance');
    return view('dashboard', ['balance' => $balance]);
})->middleware('auth')->name('dashboard');

// Routes your buttons need
Route::get('/wallet', function () {
    $balance = DB::table('users')->where('id', auth()->id())->value('wallet_balance');
    return view('dashboard', ['balance' => $balance]);
})->middleware('auth')->name('wallet.index');

Route::get('/airtime', function () {
    return redirect('/dashboard');
})->middleware('auth')->name('airtime');

Route::get('/data', function () {
    return redirect('/dashboard');
})->middleware('auth')->name('data');

// CHECK REAL BALANCE - DELETE AFTER USE
Route::get('/check-balance', function () {
    $user = auth()->user();
    if (!$user) return 'Not logged in';
    $balance = DB::table('users')->where('id', $user->id)->value('wallet_balance');
    return "EMAIL: {$user->email} <br> REAL DB BALANCE: ₦" . number_format($balance, 2);
})->middleware('auth');
