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
    
    if (!isset($result['data']['authorization_url'])) {
        return back()->with('error', 'Paystack error: ' . ($result['message'] ?? 'Unknown error'));
    }
    
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
    
    if (isset($result['data']['status']) && $result['data']['status'] == 'success') {
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

    // Validation
    if (empty($network) || empty($phone) || empty($amount)) {
        return back()->with('error', 'All fields are required');
    }

    if ($amount < 50) {
        return back()->with('error', 'Minimum amount is ₦50');
    }

    if (strlen($phone) !== 11) {
        return back()->with('error', 'Phone number must be 11 digits');
    }

    $balance = DB::table('users')->where('id', $user->id)->value('wallet_balance');
    if ($balance < $amount) {
        return back()->with('error', 'Insufficient balance. You have ₦' . number_format($balance, 2));
    }

    $request_id = date('YmdHis') . rand(1000, 9999);
    
    // FIXED: Correct VTpass URL + trim() on all env values
    $baseUrl = trim(env('VTPASS_ENV')) === 'sandbox'
        ? 'https://sandbox.vtpass.com/api'
        : 'https://api.vtpass.com/api';
    
    $vtpass_url = $baseUrl . '/pay';
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $vtpass_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'request_id' => $request_id,
            'serviceID' => $network,
            'amount' => $amount,
            'phone' => $phone
        ]),
        CURLOPT_HTTPHEADER => [
            'api-key: ' . trim(env('VTPASS_API_KEY')),
            'secret-key: ' . trim(env('VTPASS_SECRET')),
            'Content-Type: application/x-www-form-urlencoded'
        ],
    ));
    $response = curl_exec($curl);
    
    if (curl_errno($curl)) {
        $error = curl_error($curl);
        curl_close($curl);
        return back()->with('error', 'Connection failed: ' . $error);
    }
    
    curl_close($curl);
    $result = json_decode($response, true);

    // Handle VTpass response
    if (!isset($result['code']) || $result['code'] != '000') {
        $error_msg = $result['response_description'] ?? $result['content']['errors'] ?? 'Transaction failed';
        $full_response = json_encode($result);
        return back()->with('error', 'VTpass: ' . $error_msg . ' | Debug: ' . $full_response);
    }

    // Deduct wallet only on success
    DB::table('users')->where('id', $user->id)->decrement('wallet_balance', $amount);
    
    return redirect('/dashboard')->with('success', '₦' . $amount . ' ' . strtoupper($network) . ' airtime sent to ' . $phone);
    
})->middleware('auth')->name('airtime.buy');

Route::get('/data', function () {
    return redirect('/dashboard');
})->middleware('auth')->name('data');

Route::get('/test-deploy', function() {
    return "DEPLOY IS WORKING - " . date('Y-m-d H:i:s');
});
