<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Dashboard
Route::get('/home', function () {
    return view('dashboard');
})->middleware('auth')->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Wallet routes your dashboard.blade.php is calling
Route::get('/wallet', function () {
    return view('dashboard'); // Just show dashboard for now
})->middleware('auth')->name('wallet.index');

Route::get('/airtime', function () {
    return view('dashboard'); 
})->middleware('auth')->name('airtime');

Route::get('/data', function () {
    return view('dashboard');
})->middleware('auth')->name('data');
