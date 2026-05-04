<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () { 
    return redirect()->route('login'); 
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return 'Welcome BOSS. You don login. Dashboard go dey here.';
    })->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::get('/create-admin-12345', function() {
    $user = \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@topupking.com',
        'password' => bcrypt('password123')
    ]);
    return 'Admin created! Email: admin@topupking.com | Password: password123 | Now DELETE this route!';
});
