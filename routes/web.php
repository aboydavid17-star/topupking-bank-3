<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Fund Wallet Routes
Route::get('/fund-wallet', [WalletController::class, 'showFundForm'])
    ->middleware('auth')
    ->name('fund-wallet');

Route::post('/fund-wallet', [WalletController::class, 'initializePayment'])
    ->middleware('auth')
    ->name('fund-wallet.pay');

Route::get('/payment/callback', [WalletController::class, 'handleCallback'])
    ->middleware('auth')
    ->name('payment.callback');

// TEMP ROUTE TO CLEAR CACHE - DELETE AFTER USE
Route::get('/clear-cache', function() {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return 'Cache cleared successfully! ✅ <br><br> 
            1. Now test Fund Wallet <br> 
            2. Then DELETE this route from web.php for security <br><br>
            <a href="/dashboard" style="color:blue;">← Go to Dashboard</a>';
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Your existing routes stay here...

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Add these 3 routes for Wallet Funding
Route::middleware(['auth'])->group(function () {
    Route::get('/fund-wallet', [WalletController::class, 'showFundForm'])->name('fund.wallet');
    Route::post('/fund-wallet', [WalletController::class, 'initializePayment'])->name('fund.wallet.post');
    Route::get('/payment/callback', [WalletController::class, 'paymentCallback'])->name('payment.callback');
});

require __DIR__.'/auth.php';
