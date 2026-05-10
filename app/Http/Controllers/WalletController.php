<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $wallet = $user->wallet()->firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        $transactions = $user->transactions()
            ->latest()
            ->take(10)
            ->get();

        return view('wallet.index', [
            'wallet' => $wallet,
            'transactions' => $transactions
        ]);
    }

    public function fund()
    {
        return view('wallet.fund');
    }

    public function initializePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $user = Auth::user();
        $amount = $request->amount * 100; // Paystack uses kobo

        $paymentData = [
            'email' => $user->email,
            'amount' => $amount,
            'callback_url' => route('wallet.verify'),
            'metadata' => [
                'user_id' => $user->id,
                'type' => 'wallet_funding'
            ]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($paymentData),
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return back()->with('error', 'Payment initialization failed');
        }

        $result = json_decode($response, true);

        if ($result['status']) {
            return redirect($result['data']['authorization_url']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    public function verifyPayment(Request $request)
    {
        $reference = $request->reference;
        
        if (!$reference) {
            return redirect()->route('wallet.index')->with('error', 'No reference supplied');
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return redirect()->route('wallet.index')->with('error', 'Payment verification failed');
        }

        $result = json_decode($response, true);

        if ($result['status'] && $result['data']['status'] == 'success') {
            $user = Auth::user();
            $amount = $result['data']['amount'] / 100; // Convert from kobo

            // Credit wallet
            $wallet = $user->wallet()->firstOrCreate(['user_id' => $user->id]);
            $wallet->increment('balance', $amount);

            // Create transaction
            $user->transactions()->create([
                'type' => 'credit',
                'amount' => $amount,
                'description' => 'Wallet Funding via Paystack',
                'reference' => $reference,
                'status' => 'success'
            ]);

            return redirect()->route('wallet.index')->with('success', 'Wallet funded successfully');
        } else {
            return redirect()->route('wallet.index')->with('error', 'Payment not successful');
        }
    }
}
