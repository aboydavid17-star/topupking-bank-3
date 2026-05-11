<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return <<<HTML
<!DOCTYPE html>
<html>
<head><title>TopupKing</title><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="font-family:Arial;text-align:center;padding:50px;background:#f4f4f4;">
<h1>Welcome to TopupKing</h1>
<p>Fast & Reliable VTU Services</p>
<a href="/dashboard" style="padding:15px 30px;background:#007bff;color:white;text-decoration:none;border-radius:5px;margin:10px;display:inline-block;">Dashboard</a>
<a href="/buy-airtime" style="padding:15px 30px;background:#28a745;color:white;text-decoration:none;border-radius:5px;margin:10px;display:inline-block;">Buy Airtime</a>
</body></html>
HTML;
});

Route::get('/dashboard', function () {
    return <<<HTML
<!DOCTYPE html>
<html>
<head><title>Dashboard</title><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="font-family:Arial;padding:40px;background:#f8f9fa;">
<div style="max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:10px;">
<h1>Dashboard</h1>
<div style="background:#e7f3ff;padding:20px;border-radius:8px;margin:20px 0;">
<h3>Wallet Balance</h3><h2 style="color:#007bff;">₦10,000.00</h2><small>Test Mode</small></div>
<a href="/buy-airtime" style="padding:12px 24px;background:#28a745;color:white;text-decoration:none;border-radius:5px;display:inline-block;">Buy Airtime</a>
<br><br><a href="/" style="color:#6c757d;">← Back to Home</a></div></body></html>
HTML;
});

Route::get('/buy-airtime', function () {
    $error = session('error') ? '<div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin-bottom:20px;">'.session('error').'</div>' : '';
    $success = session('success') ? '<div style="background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin-bottom:20px;">'.session('success').'</div>' : '';
    $token = csrf_token();
    
    return <<<HTML
<!DOCTYPE html>
<html>
<head><title>Buy Airtime</title><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="font-family:Arial;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;margin:0;">
<div style="max-width:450px;margin:30px auto;background:white;padding:30px;border-radius:15px;">
<h2 style="text-align:center;color:#333;margin-bottom:30px;">Buy Airtime Instantly</h2>
{$error}{$success}
<form method="POST" action="/buy-airtime">
<input type="hidden" name="_token" value="{$token}">
<div style="margin-bottom:20px;"><label style="display:block;margin-bottom:8px;font-weight:bold;">Select Network</label>
<select name="network" required style="padding:12px;width:100%;border:2px solid #ddd;border-radius:8px;font-size:16px;box-sizing:border-box;">
<option value="">-- Choose Network --</option><option value="mtn">MTN</option><option value="glo">GLO</option><option value="airtel">AIRTEL</option><option value="9mobile">9MOBILE</option>
</select></div>
<div style="margin-bottom:20px;"><label style="display:block;margin-bottom:8px;font-weight:bold;">Phone Number</label>
<input type="tel" name="phone" required placeholder="08012345678" maxlength="11" style="padding:12px;width:100%;border:2px solid #ddd;border-radius:8px;font-size:16px;box-sizing:border-box;"></div>
<div style="margin-bottom:25px;"><label style="display:block;margin-bottom:8px;font-weight:bold;">Amount (₦)</label>
<input type="number" name="amount" required placeholder="100" min="50" max="10000" style="padding:12px;width:100%;border:2px solid #ddd;border-radius:8px;font-size:16px;box-sizing:border-box;">
<small style="color:#777;">Minimum: ₦50</small></div>
<button type="submit" style="padding:15px;width:100%;background:#28a745;color:white;border:none;border-radius:8px;cursor:pointer;font-size:18px;font-weight:bold;">Buy Airtime Now</button>
</form><p style="text-align:center;margin-top:20px;"><a href="/dashboard" style="color:#667eea;text-decoration:none;">← Back to Dashboard</a></p>
</div></body></html>
HTML;
});

Route::post('/buy-airtime', function () {
    $network = strtolower(request('network'));
    $phone = request('phone');
    $amount = request('amount');

    if (empty($network) || empty($phone) || empty($amount)) return back()->with('error', 'All fields are required');
    if ($amount < 50) return back()->with('error', 'Minimum amount is ₦50');
    if (strlen($phone) !== 11) return back()->with('error', 'Phone must be 11 digits');

    $request_id = date('YmdHis') . rand(1000, 9999);
    $baseUrl = trim(env('VTPASS_ENV')) === 'sandbox' ? 'https://sandbox.vtpass.com/api' : 'https://api.vtpass.com/api';
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl . '/pay',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(['request_id' => $request_id, 'serviceID' => $network, 'amount' => $amount, 'phone' => $phone]),
        CURLOPT_HTTPHEADER => ['api-key: ' . trim(env('VTPASS_API_KEY')), 'secret-key: ' . trim(env('VTPASS_SECRET')), 'Content-Type: application/x-www-form-urlencoded'],
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);

    if (!isset($result['code']) || $result['code'] != '000') {
        $error_msg = $result['response_description'] ?? 'Transaction failed. Check VTpass keys in Render.';
        return back()->with('error', 'VTpass: ' . $error_msg);
    }

    return redirect('/buy-airtime')->with('success', 'SUCCESS! ₦' . number_format($amount) . ' ' . strtoupper($network) . ' airtime sent to ' . $phone);
});

Route::get('/test123', function () {
    return '<h1>BOSS IT WORKS</h1>';
});
