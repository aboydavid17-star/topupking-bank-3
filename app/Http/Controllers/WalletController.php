<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('wallet.index', [
            'balance' => $user->wallet_balance ?? 0,
            'user' => $user
        ]);
    }

    public function create()
    {
        return view('wallet.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);
        
        // We no dey use this anymore since Paystack handles it
        return redirect()->route('wallet.create');
    }

    public function verify($reference)
    {
        $secretKey = env('PAYSTACK_SECRET_KEY');
        
        if (!$secretKey) {
            return redirect()->route('wallet.index')->with('error', 'Paystack secret key not configured');
        }
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $secretKey,
                "Cache-Control: no-cache",
            ],
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            return redirect()->route('wallet.index')->with('error', 'Payment verification failed: ' . $err);
        }
        
        $result = json_decode($response);
        
        if (isset($result->data->status) && $result->data->status == 'success') {
            $amount = $result->data->amount / 100; // Convert from kobo to naira
            $email = $result->data->customer->email;
            
            // Verify say na the logged in user email
            if ($email !== auth()->user()->email) {
                return redirect()->route('wallet.index')->with('error', 'Email mismatch. Payment failed.');
            }
            
            // Credit user wallet
            $user = auth()->user();
            $user->wallet_balance += $amount;
            $user->save();
            
            return redirect()->route('wallet.index')->with('success', 'Wallet funded successfully! ₦' . number_format($amount, 2) . ' added to your balance.');
        }
        
        return redirect()->route('wallet.index')->with('error', 'Payment failed or was not successful');
    }
}
