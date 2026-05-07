<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return 'Login page';
    })->name('login');

    Route::get('register', function () {
        return 'Register page';
    })->name('register');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', function () {
        return 'Logout';
    })->name('logout');
});
