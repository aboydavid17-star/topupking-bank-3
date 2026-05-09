public function handleGatewayCallback()
{
    $reference = request()->query('reference');
    
    if (empty($reference)) {
        return redirect()->route('dashboard')->with('error', 'No transaction reference supplied');
    }

    $secretKey = env('PAYSTACK_SECRET_KEY');
    
    // HARDCODED URL - NO CONFIG NEEDED
    $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $secretKey,
        "Cache-Control: no-cache",
    ]);
    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        \Log::error('Paystack cURL Error: ' . $err);
        return redirect()->route('dashboard')->with('error', 'Payment verification failed');
    }

    if ($httpcode != 200) {
        \Log::error('Paystack HTTP Error: ' . $httpcode . ' Response: ' . $response);
        return redirect()->route('dashboard')->with('error', 'Payment verification failed');
    }

    $result = json_decode($response, true);

    if (!$result['status'] || $result['data']['status'] !== 'success') {
        return redirect()->route('dashboard')->with('error', 'Payment was not successful');
    }

    $user = auth()->user();
    $amount = $result['data']['amount'] / 100; // Convert from kobo to naira
    
    // Prevent double funding
    $exists = \App\Models\Transaction::where('reference', $reference)->exists();
    if ($exists) {
        return redirect()->route('dashboard')->with('info', 'Transaction already processed');
    }

    \DB::transaction(function () use ($user, $amount, $reference) {
        $balanceBefore = $user->wallet;
        
        $user->increment('wallet', $amount);
        $user->refresh();
        
        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'purpose' => 'funding',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $user->wallet,
            'reference' => $reference,
            'status' => 'successful',
            'description' => 'Wallet funding via Paystack'
        ]);
    });

    return redirect()->route('dashboard')->with('success', 'Wallet funded successfully! ₦' . number_format($amount, 2));
}
