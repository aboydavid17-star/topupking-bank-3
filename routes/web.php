<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| TOPUPKING - COMPLETE WORKING FILE
| No login, no blade files, no 404. Just works.
|--------------------------------------------------------------------------
*/

// 1. HOMEPAGE
Route::get('/', function () {
    return '<h1>TopupKing Homepage</h1><p><a href="/dashboard">Go to Dashboard</a></p><p><a href="/buy-airtime">Buy Airtime</a></p>';
});

// 2. DASHBOARD - NO LOGIN NEEDED FOR NOW
Route::get('/dashboard', function () {
    return '
    <html>
    <head><title>Dashboard</title></head>
    <body style="font-family: Arial; padding: 40px; background: #f0f0f0;">
        <h1>TopupKing Dashboard</h1>
        <p><strong>Wallet Balance: ₦1,000.00</strong> - Test Mode</p>
        <hr>
        <h3>Quick Actions</h3>
        <p><a href="/buy-airtime" style="padding: 10px 20px; background: green; color: white; text-decoration: none;">Buy Airtime</a></p>
    </body>
    </html>
    ';
});

// 3. BUY AIRTIME PAGE
Route::get('/buy-airtime', function () {
    return '
    <html>
    <head><title>Buy Airtime</title></head>
    <body style="font-family: Arial; padding: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
        <div style="max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px;">
            <h2 style="text-align: center;">Buy Airtime</h2>
            
            '.(session('error') ? '<div style="background: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px;">'.session('error').'</div>' : '').'
            '.(session('success') ? '<div style="background: #ccffcc; color: #006600; padding: 10px; border-radius: 5px; margin-bottom: 15px;">'.session('success').'</div>' : '').'
            
            <form method="POST" action="/buy-airtime">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                
                <p>
                    <label><strong>Network:</strong></label><br>
                    <select name="network" required style="padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="">Select Network</option>
                        <option value="mtn">MTN</option>
                        <option value="glo">GLO</option>
                        <option value="airtel">AIRTEL</option>
                        <option value="9mobile">9MOBILE</option>
                    </select>
                </p>
                
                <p>
                    <label><strong>Phone Number:</strong></label><br>
                    <input type="text" name="phone" required placeholder="08012345678" maxlength="11" style="padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                </p>
                
                <p>
                    <label><strong>Amount (₦):</strong></label><br>
                    <input type="number" name="amount" required placeholder="100" min="50" max="10000" style="padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 5px;">
                    <small>Min: ₦50 | Max: ₦10,000</small>
                </p>
                
                <p>
                    <button type="submit" style="padding: 12px; width: 100%; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                        Buy Airtime Now
                    </button>
                </p>
                
                <p style="text-align: center;"><a href="/dashboard">Back to Dashboard</a></p>
            </form>
        </div>
    </body>
    </html>
    ';
})->name('airtime.form');

// 4. PROCESS BUY AIRTIME
Route::post('/buy-airtime', function () {
    $network = strtolower(request('network'));
    $phone = request('phone');
    $amount = request('amount');

    // Validation
    if (empty($network) || empty($phone) || empty($amount)) {
        return back()->with('error', 'All fields are required');
    }
    if ($amount < 50) return back()->with('error', 'Minimum amount is ₦50');
    if (strlen($phone) !== 11) return back()->with('error', 'Phone must be 11 digits');

    // VTPASS API CALL
    $request_id = date('Y
