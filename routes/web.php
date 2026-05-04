<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AirtimeController;
use App\Http\Controllers\DataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Airtime Routes
    Route::get('/airtime', [AirtimeController::class, 'index'])->name('airtime.index');
    Route::post('/airtime/buy', [AirtimeController::class, 'buy'])->name('airtime.buy');

    // Data Routes  
    Route::get('/data', [DataController::class, 'index'])->name('data.index');
    Route::post('/data/buy', [DataController::class, 'buy'])->name('data.buy');
});

require __DIR__.'/auth.php';
