<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AirtimeController extends Controller
{
    public function buyAirtime(Request $request)
    {
        $request->validate([
            'network' => 'required|string',
            'phone' => 'required|string|min:11|max:11',
            'amount' => 'required|numeric|min:50|max:10000',
        ]);

        $network = strtolower($request->network); // mtn, glo, airtel, 9mobile
        $phone = $request->phone;
        $amount = $request->amount;

        // 1. Check user wallet balance first
        if (auth()->user()->wallet_balance < $amount) {
            return back()->with('error', 'Insufficient wallet balance');
        }

        // 2. Generate unique request_id for VTpass
        $requestId = date('YmdHis') . Str::random(4);

        // 3. SET CORRECT VTPASS URL BASED ON ENVIRONMENT
        $baseUrl = env('VTPASS_ENV') == 'sandbox' 
            ? 'https://sandbox.vtpass.com/api' 
            : 'https://api.vtpass.com/api';
        
        $url = $baseUrl . '/pay';

        // 4. Map network names to VTpass serviceID
        $serviceMap = [
            'mtn' => 'mtn',
            'glo' => 'glo', 
            'airtel' => 'airtel',
            '9mobile' => 'etisalat'
        ];

        $serviceID = $serviceMap[$network] ?? 'mtn';

        // 5. Prepare payload
        $payload = [
            'request_id' => $requestId,
            'serviceID' => $serviceID,
            'amount' => $amount,
            'phone' => $phone
        ];

        // 6. Prepare headers with YOUR RENDER ENV KEYS
        $headers = [
            'api-key' => env('VTPASS_API_KEY'),      // 577a2f1ea8367cf7baaf453ce7b79ba9
            'secret-key' => env('VTPASS_SECRET'),    // SK_24245b7d31d5ea169652d6b3...
            'Content-Type' => 'application/json'
        ];

        // 7. DEBUG LOG - Check what's being sent
        Log::info('VTpass Request', [
            'url' => $url,
            'env' => env('VTPASS_ENV'),
            'api_key_used' => env('VTPASS_API_KEY'),
            'payload' => $payload
        ]);

        try {
            // 8. Send request to VTpass
            $response = Http::withHeaders($headers)->post($url, $payload);
            
            $result = $response->json();

            // 9. DEBUG LOG - Check VTpass response
            Log::info('VTpass Response', $result);

            // 10. Check if successful
            if (isset($result['code']) && $result['code'] == '000') {
                // Deduct from user wallet
                auth()->user()->decrement('wallet_balance', $amount);
                
                // Save transaction
                // Transaction::create([...]);

                return back()->with('success', "Success: ₦{$amount} airtime sent to {$phone}");
            } else {
                // Show VTpass error
                $errorMsg = $result['response_description'] ?? $result['message'] ?? 'VTpass error';
                return back()->with('error', "VTpass: {$errorMsg} | Debug: " . json_encode($result));
            }

        } catch (\Exception $e) {
            Log::error('VTpass Exception: ' . $e->getMessage());
            return back()->with('error', 'VTpass: No response from VTpass | Debug: ' . $e->getMessage());
        }
    }
}
