<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Dashboard
Route::get('/home', function () {
    $balance = DB::table('users')->where('id', auth()->id())->value('wallet_balance');
    return view('dashboard', ['balance' => $balance]);
})->middleware('auth')->name('home');

Route::get('/dashboard', function () {
    $balance = DB::table('users')->where('id', auth()->id())->value('wallet_balance');
    return view('dashboard', ['balance' => $balance]);
})->middleware('auth')->name('dashboard');

Route::get('/wallet', function () {
    return redirect('/dashboard');
})->middleware('auth')->name('wallet.index');

// FUND WALLET
Route::get('/fund-wallet', function () {
    return view('fund-wallet');
})->middleware('auth')->name('fund.wallet');

Route::post('/fund-wallet/initialize', function () {
    $amount = request('amount') * 100;
    $email = auth()->user()->email;
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'email' => $email,
            'amount' => $amount,
            'callback_url' => url('/fund-wallet/callback'),
            'metadata' => ['user_id' => auth()->id()]
        ]),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
            "Content-Type: application/json"
        ],
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    
    return redirect($result['data']['authorization_url']);
})->middleware('auth')->name('fund.initialize');

Route::get('/fund-wallet/callback', function () {
    $reference = request('reference');
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY')
        ],
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    
    if ($result['data']['status'] == 'success') {
        $amount = $result['data']['amount'] / 100;
        $user_id = $result['data']['metadata']['user_id'];
        
        DB::table('users')->where('id', $user_id)->increment('wallet_balance', $amount);
        
        return redirect('/dashboard')->with('success', 'Wallet funded with ₦' . number_format($amount, 2));
    }
    
    return redirect('/dashboard')->with('error', 'Payment failed');
})->middleware('auth');

// BUY AIRTIME
Route::get('/airtime', function () {
    return redirect()->route('airtime.form');
})->middleware('auth')->name('airtime');

Route::get('/buy-airtime', function () {
    return view('buy-airtime');
})->middleware('auth')->name('airtime.form');

Route::post('/buy-airtime', function () {
    $user = auth()->user();
    $amount = request('amount');
    $phone = request('phone');
    $network = strtolower(request('
