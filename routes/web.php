<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});

// AUTH ROUTES - NO CONTROLLERS NEEDED
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);
 
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/wallet');
    }
 
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);
 
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
 
    Auth::login($user);
    return redirect('/wallet');
});

// WALLET ROUTES - Protected by auth
Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::get('/fund-wallet', [WalletController::class, 'fundWalletPage'])->name('wallet.fund');
    Route::post('/fund-wallet', [WalletController::class, 'fundWallet'])->name('wallet.fund.post');
    Route::get('/buy-data', [WalletController::class, 'buyDataPage'])->name('wallet.buy-data');
    Route::post('/buy-data', [WalletController::class, 'buyData'])->name('wallet.buy-data.post');
});
