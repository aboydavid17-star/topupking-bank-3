<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

session_start();

// HELPER: Check if logged in
function authCheck() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    return DB::table('users')->find($_SESSION['user_id']);
}

// HOMEPAGE
Route::get('/', function () {
    return <<<HTML
<!DOCTYPE html><html><head><title>TopupKing</title><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="font-family:Arial;text-align:center;padding:50px;background:#f4f4f4;">
<h1>Welcome to TopupKing</h1><p>Fast & Reliable VTU Services</p>
<a href="/register" style="padding:15px 30px;background:#007bff;color:white;text-decoration:none;border-radius:5px;margin:10px;display:inline-block;">Sign Up</a>
<a href="/login" style="padding:15px 30px;background:#28a745;color:white;text-decoration:none;border-radius:5px;margin:10px;display:inline-block;">Login</a>
</body></html>
HTML;
});

// REGISTER PAGE
Route::get('/register', function () {
    $error = session('error') ? '<div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin-bottom:20px;">'.session('error').'</div>' : '';
    return <<<HTML
<!DOCTYPE html><html><head><title>Register</title><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="font-family:Arial;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;">
<div style="max-width:400px;margin:30px auto;background:white;padding:30px;border-radius:15px;">
<h2 style="text-align:center;">Create Account</h2>{$error}
<form method="POST" action="/register">
<input type="hidden" name="_token" value=<?php echo csrf_token(); ?>
<input name="name" required placeholder="Full Name" style="padding:12px;width:100%;margin-bottom:15px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<input name="email" type="email" required placeholder="Email" style="padding:12px;width:100%;margin-bottom:15px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<input name="phone" required placeholder="Phone 08012345678" maxlength="11" style="padding:12px;width:100%;margin-bottom:15px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<input name="password" type="password" required placeholder="Password" style="padding:12px;width:100%;margin-bottom:20px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<button style="padding:15px;width:100%;background:#28a745;color:white;border:none;border-radius:8px;font-size:16px;">Register</button>
</form><p style="text-align:center;margin-top:15px;">Have account? <a href="/login">Login</a></p></div></body></html>
HTML;
});

// REGISTER POST
Route::post('/register', function () {
    $exists = DB::table('users')->where('email', request('email'))->orWhere('phone', request('phone'))->first();
    if ($exists) return back()->with('error', 'Email or phone already exists');
    
    $userId = DB::table('users')->insertGetId([
        'name' => request('name'),
        'email' => request('email'),
        'phone' => request('phone'),
        'password' => Hash::make(request('password')),
        'wallet_balance' => 0
    ]);
    
    $_SESSION['user_id'] = $userId;
    return redirect('/dashboard');
});

// LOGIN PAGE
Route::get('/login', function () {
    $error = session('error') ? '<div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin-bottom:20px;">'.session('error').'</div>' : '';
    return <<<HTML
<!DOCTYPE html><html><head><title>Login</title><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="font-family:Arial;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;">
<div style="max-width:400px;margin:30px auto;background:white;padding:30px;border-radius:15px;">
<h2 style="text-align:center;">Login</h2>{$error}
<form method="POST" action="/login">
<input type="hidden" name="_token" value=<?php echo csrf_token(); ?>

<input name="email" type="email" required placeholder="Email" style="padding:12px;width:100%;margin-bottom:15px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<input name="password" type="password" required placeholder="Password" style="padding:12px;width:100%;margin-bottom:20px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<button style="padding:15px;width:100%;background:#007bff;color:white;border:none;border-radius:8px;font-size:16px;">Login</button>
</form><p style="text-align:center;margin-top:15px;">No account? <a href="/register">Register</a></p></div></body></html>
HTML;
});

// LOGIN POST
Route::post('/login', function () {
    $user = DB::table('users')->where('email', request('email'))->first();
    if (!$user || !Hash::check(request('password'), $user->password)) {
        return back()->with('error', 'Invalid email or password');
    }
    $_SESSION['user_id'] = $user->id;
    return redirect('/dashboard');
});

// LOGOUT
Route::get('/logout', function () {
    session_destroy();
    return redirect('/');
});

// DASHBOARD - NEEDS LOGIN
Route::get('/dashboard', function () {
    $user = authCheck();
    $success = session('success') ? '<div style="background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin-bottom:20px;">'.session('success').'</div>' : '';
    return <<<HTML
<!DOCTYPE html><html><head><title>Dashboard</title><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="font-family:Arial;padding:20px;background:#f8f9fa;">
<div style="max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:10px;">
<div style="display:flex;justify-content:space-between;align-items:center;"><h1>Welcome, {$user->name}</h1><a href="/logout" style="color:red;">Logout</a></div>
{$success}
<div style="background:#e7f3ff;padding:20px;border-radius:8px;margin:20px 0;">
<h3>Wallet Balance</h3><h2 style="color:#007bff;">₦{$user->wallet_balance}</h2></div>
<a href="/fund-wallet" style="padding:12px 24px;background:#ffc107;color:black;text-decoration:none;border-radius:5px;display:inline-block;margin:5px;">Fund Wallet</a>
<a href="/buy-airtime" style="padding:12px 24px;background:#28a745;color:white;text-decoration:none;border-radius:5px;display:inline-block;margin:5px;">Buy Airtime</a>
</div></body></html>
HTML;
});

// FUND WALLET PAGE
Route::get('/fund-wallet', function () {
    $user = authCheck();
    return <<<HTML
<!DOCTYPE html><html><head><title>Fund Wallet</title><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="font-family:Arial;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;">
<div style="max-width:400px;margin:30px auto;background:white;padding:30px;border-radius:15px;">
<h2 style="text-align:center;">Fund Wallet</h2>
<form method="POST" action="/fund-wallet">
<input type="hidden" name="_token" value=<?php echo csrf_token(); ?>

<input name="amount" type="number" required placeholder="Amount ₦" min="100" style="padding:12px;width:100%;margin-bottom:20px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<button style="padding:15px;width:100%;background:#007bff;color:white;border:none;border-radius:8px;font-size:16px;">Pay with Paystack</button>
</form><p style="text-align:center;margin-top:15px;"><a href="/dashboard">← Dashboard</a></p></div></body></html>
HTML;
});

// INITIATE PAYSTACK
Route::post('/fund-wallet', function () {
    $user = authCheck();
    $amount = request('amount') * 100; // Paystack uses kobo
    $ref = 'TK_' . time() . rand(1000,9999);
    
    DB::table('transactions')->insert([
        'user_id' => $user->id,
        'type' => 'funding',
        'amount' => request('amount'),
        'status' => 'pending',
        'reference' => $ref,
        'description' => 'Wallet Funding'
    ]);
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'email' => $user->email,
            'amount' => $amount,
            'reference' => $ref,
            'callback_url' => url('/paystack/callback')
        ]),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
            "Content-Type: application/json"
        ],
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    
    if ($result['status']) {
        return redirect($result['data']['authorization_url']);
    }
    return back()->with('error', 'Payment init failed');
});

// PAYSTACK CALLBACK
Route::get('/paystack/callback', function () {
    $ref = request('reference');
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $ref,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer " . env('PAYSTACK_SECRET_KEY')],
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    
    if ($result['status'] && $result['data']['status'] == 'success') {
        $txn = DB::table('transactions')->where('reference', $ref)->first();
        if ($txn && $txn->status == 'pending') {
            DB::table('users')->where('id', $txn->user_id)->increment('wallet_balance', $txn->amount);
            DB::table('transactions')->where('id', $txn->id)->update(['status' => 'success']);
            return redirect('/dashboard')->with('success', 'Wallet funded with ₦' . number_format($txn->amount));
        }
    }
    return redirect('/dashboard')->with('error', 'Payment verification failed');
});

// BUY AIRTIME - NEEDS LOGIN
Route::get('/buy-airtime', function () {
    $user = authCheck();
    $error = session('error') ? '<div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin-bottom:20px;">'.session('error').'</div>' : '';
    $success = session('success') ? '<div style="background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin-bottom:20px;">'.session('success').'</div>' : '';
    $token = csrf_token();
    return <<<HTML
<!DOCTYPE html><html><head><title>Buy Airtime</title><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="font-family:Arial;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;">
<div style="max-width:450px;margin:30px auto;background:white;padding:30px;border-radius:15px;">
<h2 style="text-align:center;">Buy Airtime</h2><p style="text-align:center;">Balance: ₦{$user->wallet_balance}</p>
{$error}{$success}
<form method="POST" action="/buy-airtime">
<input type="hidden" name="_token" value="{$token}">
<select name="network" required style="padding:12px;width:100%;margin-bottom:15px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<option value="">-- Network --</option><option value="mtn">MTN</option><option value="glo">GLO</option><option value="airtel">AIRTEL</option><option value="9mobile">9MOBILE</option>
</select>
<input name="phone" required placeholder="08012345678" maxlength="11" style="padding:12px;width:100%;margin-bottom:15px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<input name="amount" type="number" required placeholder="Amount ₦" min="50" style="padding:12px;width:100%;margin-bottom:20px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;">
<button style="padding:15px;width:100%;background:#28a745;color:white;border:none;border-radius:8px;font-size:16px;">Buy Now</button>
</form><p style="text-align:center;margin-top:15px;"><a href="/dashboard">← Dashboard</a></p></div></body></html>
HTML;
});

// BUY AIRTIME POST - DEDUCTS WALLET
Route::post('/buy-airtime', function () {
    $user = authCheck();
    $amount = request('amount');
    $network = strtolower(request('network'));
    $phone = request('phone');

    if ($user->wallet_balance < $amount) return back()->with('error', 'Insufficient wallet balance. Fund wallet first.');
    if ($amount < 50) return back()->with('error', 'Minimum ₦50');

    // Deduct wallet first
    DB::table('users')->where('id', $user->id)->decrement('wallet_balance', $amount);
    
    // Call VTpass
    $request_id = date('YmdHis') . rand(1000, 9999);
    $baseUrl = trim(env('VTPASS_ENV')) === 'sandbox' ? 'https://sandbox.vtpass.com/api' : 'https://api.vtpass.com/api';
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl . '/pay',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(['request_id' => $request_id, 'serviceID' => $network, 'amount' => $amount, 'phone' => $phone]),
        CURLOPT_HTTPHEADER => ['api-key: ' . env('VTPASS_API_KEY'), 'secret-key: ' . env('VTPASS_SECRET')],
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);

    if (!isset($result['code']) || $result['code'] != '000') {
        // Refund wallet if VTpass fails
        DB::table('users')->where('id', $user->id)->increment('wallet_balance', $amount);
        return back()->with('error', 'VTpass: ' . ($result['response_description'] ?? 'Transaction failed'));
    }

    DB::table('transactions')->insert([
        'user_id' => $user->id,
        'type' => 'airtime',
        'amount' => $amount,
        'status' => 'success',
        'reference' => $request_id,
        'description' => strtoupper($network) . ' Airtime to ' . $phone
    ]);

    return redirect('/buy-airtime')->with('success', 'SUCCESS! ₦' . $amount . ' ' . strtoupper($network) . ' sent to ' . $phone);
});
// ==================== USER AUTH SYSTEM ====================

// REGISTER PAGE
Route::get('/register', function () {
    return <<<HTML
<!DOCTYPE html><html><head><title>Sign Up - TopupKing</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head><body style="font-family:Arial;text-align:center;padding:20px;background:#f5f5f5">
<h2>Create Account</h2>
<form method="POST" action="/register" style="max-width:400px;margin:0 auto;background:white;padding:20px;border-radius:10px">
    <input type="text" name="name" placeholder="Full Name" required style="width:100%;padding:12px;margin:8px 0;border:1px solid #ddd;border-radius:5px"><br>
    <input type="email" name="email" placeholder="Email" required style="width:100%;padding:12px;margin:8px 0;border:1px solid #ddd;border-radius:5px"><br>
    <input type="text" name="phone" placeholder="Phone (11 digits)" required style="width:100%;padding:12px;margin:8px 0;border:1px solid #ddd;border-radius:5px"><br>
    <input type="password" name="password" placeholder="Password" required style="width:100%;padding:12px;margin:8px 0;border:1px solid #ddd;border-radius:5px"><br>
    <button type="submit" style="width:100%;padding:12px;background:#00a859;color:white;border:none;border-radius:5px;font-size:16px">Sign Up</button>
</form>
<p><a href="/login">Already have account? Login</a></p>
</body></html>
HTML;
});

// REGISTER POST
Route::post('/register', function () {
    $name = request('name');
    $email = request('email');
    $phone = request('phone');
    $password = Hash::make(request('password'));
    
    try {
        DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'wallet_balance' => 0.00
        ]);
        return redirect('/login')->with('success', 'Account created! Please login');
    } catch (Exception $e) {
        return back()->with('error', 'Email or phone already exists');
    }
});

// LOGIN PAGE
Route::get('/login', function () {
    return <<<HTML
<!DOCTYPE html><html><head><title>Login - TopupKing</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head><body style="font-family:Arial;text-align:center;padding:20px;background:#f5f5f5">
<h2>Login</h2>
<form method="POST" action="/login" style="max-width:400px;margin:0 auto;background:white;padding:20px;border-radius:10px">
    <input type="email" name="email" placeholder="Email" required style="width:100%;padding:12px;margin:8px 0;border:1px solid #ddd;border-radius:5px"><br>
    <input type="password" name="password" placeholder="Password" required style="width:100%;padding:12px;margin:8px 0;border:1px solid #ddd;border-radius:5px"><br>
    <button type="submit" style="width:100%;padding:12px;background:#00a859;color:white;border:none;border-radius:5px;font-size:16px">Login</button>
</form>
<p><a href="/register">Don't have account? Sign Up</a></p>
</body></html>
HTML;
});

// LOGIN POST
Route::post('/login', function () {
    $email = request('email');
    $password = request('password');
    
    $user = DB::table('users')->where('email', $email)->first();
    
    if ($user && Hash::check($password, $user->password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        return redirect('/dashboard');
    }
    return back()->with('error', 'Invalid email or password');
});

// DASHBOARD - WALLET
Route::get('/dashboard', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    
    $user = DB::table('users')->find($_SESSION['user_id']);
    
    return <<<HTML
<!DOCTYPE html><html><head><title>Dashboard - TopupKing</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head><body style="font-family:Arial;text-align:center;padding:20px;background:#f5f5f5">
<h2>Welcome, {$user->name}!</h2>
<div style="max-width:400px;margin:20px auto;background:white;padding:20px;border-radius:10px">
    <h3>Wallet Balance</h3>
    <h1 style="color:#00a859">₦{$user->wallet_balance}</h1>
</div>
<a href="/buy-airtime" style="display:block;padding:15px;margin:10px;background:#00a859;color:white;text-decoration:none;border-radius:5px">Buy Airtime</a>
<a href="/fund-wallet" style="display:block;padding:15px;margin:10px;background:#0066cc;color:white;text-decoration:none;border-radius:5px">Fund Wallet</a>
<a href="/logout" style="display:block;padding:15px;margin:10px;background:#cc0000;color:white;text-decoration:none;border-radius:5px">Logout</a>
</body></html>
HTML;
});

// LOGOUT
Route::get('/logout', function () {
    session_destroy();
    return redirect('/');
});
