<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    return '<h1>TopupKing Homepage</h1>';
});

// BUY AIRTIME PAGE - THIS WILL WORK
Route::get('/buy-airtime', function () {
    return '
    <html>
    <head><title>Buy Airtime</title></head>
    <body style="font-family: Arial; padding: 40px;">
        <h1>Buy Airtime</h1>
        <form method="POST" action="/buy-airtime">
            <input type="hidden" name="_token" value="'.csrf_token().'">
            
            <p>
                <label>Network:</label><br>
                <select name="network" required style="padding: 8px; width: 300px;">
                    <option value="">Select Network</option>
                    <option value="mtn">MTN</option>
                    <option value="glo">GLO</option>
                    <option value="airtel">AIRTEL</option>
                    <option value="9mobile">9MOBILE</option>
                </select>
            </p>
            
            <p>
                <label>Phone Number:</label><br>
                <input type="text" name="phone" required placeholder="08012345678" style="padding: 8px; width: 300px;">
            </p>
            
            <p>
                <label>Amount:</label><br>
                <input type="number" name="amount" required placeholder="100" style="padding: 8px; width: 300px;">
            </p>
            
            <p>
                <button type="submit" style="padding: 10px 20px; background: green; color: white; border: none; cursor: pointer;">
                    Buy Airtime
                </button>
            </p>
        </form>
    </body>
    </html>
    ';
})->name('airtime.form');

// PROCESS BUY AIRTIME
Route::post('/buy-airtime', function () {
    $network = strtolower(request('network'));
    $phone = request('phone');
    $amount = request('amount');

    // Basic validation
    if (empty($network) || empty($phone) || empty($amount)) {
        return 'Error: All fields are required. <a href="/buy-airtime">Go back</a>';
    }

    if ($amount < 50) {
        return 'Error: Minimum amount is ₦50. <a href="/buy-airtime">Go back</a>';
    }

    if (strlen($phone) !== 11) {
        return 'Error: Phone must be 11 digits. <a href="/buy-airtime">Go back</a>';
    }

    // VTPASS API CALL
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
        return 'Error: ' . $error_msg . ' <a href="/buy-airtime">Go back</a>';
    }

    return '<h1>SUCCESS!</h1><p>₦' . $amount . ' ' . strtoupper($network) . ' airtime sent to ' . $phone . '</p><a href="/buy-airtime">Buy Again</a>';
})->name('airtime.buy');

// TEST ROUTE
Route::get('/test123', function () {
    return 'BOSS IT WORKS';
});
