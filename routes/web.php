<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes - TopupKing
|--------------------------------------------------------------------------
*/

// Homepage redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// AUTH ROUTES - USING CLOSURES, NO AuthController NEEDED
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

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
        ])->onlyInput('email');
    });

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

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
});

// LOGOUT
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// PROTECTED ROUTES - WALLET
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/wallet');
    });
    
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::get('/fund-wallet', [WalletController::class, 'fundWalletPage'])->name('wallet.fund');
    Route::post('/fund-wallet', [WalletController::class, 'fundWallet'])->name('wallet.fund.post');
    Route::get('/buy-data', [WalletController::class, 'buyDataPage'])->name('wallet.buy-data');
    Route::post('/buy-data', [WalletController::class, 'buyData'])->name('wallet.buy-data.post');
});<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// TEMP ROUTE - DELETE AFTER USE
Route::get('/boss-run-migrate', function() {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        return "<h1>MIGRATION RESULT:</h1><pre>$output</pre><br><h2>NOW DELETE THIS ROUTE!</h2>";
    } catch (Exception $e) {
        return "<h1>ERROR:</h1><pre>" . $e->getMessage() . "</pre>";
    }
});
