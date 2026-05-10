<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(); // This adds /login, /register, /logout routes

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
