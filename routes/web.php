<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\WalletController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/ping', fn() => 'TOPUPKING IS ALIVE - ' . config('app.url'));

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('wallet');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('wallet'));
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    });
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    Route::post('/register', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'balance' => 0,
        ]);
 
        Auth::login($user);
        return redirect()->route('wallet');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::get('/fund', [WalletController::class, 'showFundForm'])->name('wallet.fund');
    Route::post('/fund', [WalletController::class, 'fund'])->name('wallet.fund.store');
    Route::post('/pay', [WalletController::class, 'redirectToGateway'])->name('pay');
    Route::get('/pay/callback', [WalletController::class, 'handleGatewayCallback'])->name('pay.callback');
    Route::get('/buy-data', [WalletController::class, 'showDataForm'])->name('wallet.data');
    Route::post('/buy-data', [WalletController::class, 'buyData'])->name('wallet.data.store');
    Route::get('/buy-airtime', [WalletController::class, 'showAirtimeForm'])->name('wallet.airtime');
    Route::post('/buy-airtime', [WalletController::class, 'buyAirtime'])->name('wallet.airtime.store');
    
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
