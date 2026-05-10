<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

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
    $network = strtolower(request('network'));

    $balance = DB::table('users')->where('id', $user->id)->value('wallet_balance');
    if ($balance < $amount) {
        return back()->with('error', 'Insufficient balance. You have ₦' . number_format($balance, 2));
    }

    $request_id = date('YmdHis') . rand(1000, 9999);
    
    $vtpass_url = env('VTPASS_ENV') == 'sandbox' 
        ? 'https://sandbox.vtpass.com/api/pay' 
        : 'https://vtpass.com/api/pay';
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $vtpass_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'request_id' => $request_id,
            'serviceID' => $network,
            'amount' => $amount,
            'phone' => $phone
        ]),
        CURLOPT_HTTPHEADER => [
            'api-key: ' . env('VTPASS_API_KEY'),
            'secret-key: ' . env('VTPASS_SECRET'),
            'Content-Type: application/x-www-form-urlencoded'
        ],
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);

    // SHOW FULL VTPASS ERROR - THIS IS THE KEY CHANGE
    if (!isset($result['code']) || $result['code'] != '000') {
        $error_msg = $result['response_description'] ?? 'No response from VTpass';
        $full_response = json_encode($result);
        return back()->with('error', 'VTpass: ' . $error_msg . ' | Debug: ' . $full_response);
    }

    DB::table('users')->where('id', $user->id)->decrement('wallet_balance', $amount);
    return redirect('/dashboard')->with('success', '₦' . $amount . ' airtime sent to ' . $phone);
    
})->middleware('auth')->name('airtime.buy');

Route::get('/data', function () {
    return redirect('/dashboard');
})->middleware('auth')->name('data');
