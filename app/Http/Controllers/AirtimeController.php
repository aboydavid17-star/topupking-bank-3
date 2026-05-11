<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AirtimeController extends Controller
{
    public function buyAirtime(Request $request)
    {
        // THIS LINE WILL FORCE A BLACK SCREEN WITH DEBUG DATA
        dd([
            'url' => (trim(env('VTPASS_ENV')) === 'sandbox') ? 'https://sandbox.vtpass.com/api/pay' : 'https://api.vtpass.com/api/pay',
            'env' => env('VTPASS_ENV'),
            'api_key' => env('VTPASS_API_KEY'),
            'secret' => env('VTPASS_SECRET'),
            'key_len' => strlen(env('VTPASS_API_KEY')),
            'secret_len' => strlen(env('VTPASS_SECRET'))
        ]);

        // CODE BELOW WON'T RUN BECAUSE dd() STOPS IT
        $request->validate([
            'network' => 'required|string',
            'phone' => 'required|string|digits:11',
            'amount' => 'required|numeric|min:50|max:10000',
        ]);

        $network = strtolower($request->network);
        $phone = $request->phone;
        $amount = (int) $request->amount;

        if (Auth::user()->wallet_balance < $amount) {
            return back()->with('error', 'Insufficient wallet balance');
        }

        $requestId = 'TKB' . date('YmdHis') . Str::upper(Str::random(4));

        $baseUrl = trim(env('VTPASS_ENV')) === 'sandbox'
            ? 'https://sandbox.vtpass.com/api'
            : 'https://api.vtpass.com/api';
        
        $url = $baseUrl . '/pay';

        $serviceMap = [
            'mtn' => 'mtn', 'glo' => 'glo', 'airtel' => 'airtel',
            '9mobile' => 'etisalat', 'etisalat' => 'etisalat'
        ];

        $serviceID = $serviceMap[$network] ?? null;
        if (!$serviceID) {
            return back()->with('error', 'Invalid network selected');
        }

        $payload = [
            'request_id' => $requestId,
            'serviceID' => $serviceID,
            'amount' => $amount,
            'phone' => $phone
        ];

        $headers = [
            'api-key' => trim(env('VTPASS_API_KEY')),
            'secret-key' => trim(env('VTPASS_SECRET')),
            'Content-Type' => 'application/json'
        ];

        try {
            $response = Http::timeout(30)->withHeaders($headers)->post($url, $payload);
            $result = $response->json();

            if (isset($result['code']) && $result['code'] == '000') {
                Auth::user()->decrement('wallet_balance', $amount);
                return redirect()->route('dashboard')->with('success', "Success: ₦{$amount} airtime sent to {$phone}");
            } else {
                $errorMsg = $result['response_description'] ?? $result['message'] ?? 'Unknown error';
                return back()->with('error', "VTpass: {$errorMsg}");
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Connection failed: ' . $e->getMessage());
        }
    }
}
