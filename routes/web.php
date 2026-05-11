<?php

use Illuminate\Support\Facades\DB;

// SHOW BUY AIRTIME PAGE
Route::get('/buy-airtime', function () {
    return view('buy-airtime');
})->middleware('auth')->name('airtime.form');

// PROCESS BUY AIRTIME 
Route::post('/buy-airtime', function () {
    $user = auth()->user();
    $amount = request('amount');
    $phone = request('phone');
    $network = strtolower(request('network'));

    if (empty($network) || empty($phone) || empty($amount)) {
        return back()->with('error', 'All fields are required');
    }

    if ($amount < 50) return back()->with('error', 'Minimum amount is ₦50');
    if (strlen($phone) !== 11) return back()->with('error', 'Phone must be 11 digits');

    $balance = DB::table('users')->where('id', $user->id)->value('wallet_balance');
    if ($balance < $amount) {
        return back()->with('error', 'Insufficient balance. You have ₦' . number_format($balance, 2));
    }

    $request_id = date('YmdHis') . rand(1000, 9999);
    
    $baseUrl = trim(env('VTPASS_ENV')) === 'sandbox' ? 'https://sandbox.vtpass.com/api' : 'https://api.vtpass.com/api';
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
    curl_close($curl);
    $result = json_decode($response, true);

    if (!isset($result['code']) || $result['code'] != '000') {
        $error_msg = $result['response_description'] ?? 'Transaction failed';
        return back()->with('error', 'VTpass: ' . $error_msg);
    }

    DB::table('users')->where('id', $user->id)->decrement('wallet_balance', $amount);
    return redirect('/dashboard')->with('success', '₦' . $amount . ' ' . strtoupper($network) . ' airtime sent to ' . $phone);
    
})->middleware('auth')->name('airtime.buy');
Route::get('/test123', function () {
    return 'BOSS IT WORKS';
});
Route::get('/buy-airtime', function () {
    return '<h1>Buy Airtime Works!</h1>';
})->name('airtime.form');

Route::post('/buy-airtime', function () {
    // your code 
})->name('airtime.buy');
