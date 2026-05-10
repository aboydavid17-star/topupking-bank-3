<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet()->firstOrCreate(['user_id' => $user->id]);
        $transactions = $user->transactions()->latest()->get();
        
        return view('wallet.index', compact('wallet', 'transactions'));
    }

    public function showFundForm()
    {
        return view('wallet.fund');
    }

    public function initializePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $user = Auth::user();
        $amount = $request->amount * 100;

        $paymentData = [
            'email' => $user->email,
            'amount' => $amount,
            'currency' => 'NGN',
            'callback_url' => route('wallet.verify'),
            'metadata' => [
                'user_id' => $user->id,
                'type' => 'wallet_funding'
            ]
        ];

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->post('https://api.paystack.co/transaction/initialize', $paymentData);

        $result = json_decode($response, true);

        if (!$result['status']) {
            return back()->with('error', 'Paystack Error: ' . $result['message']);
        }

        return redirect($result['data']['authorization_url']);
    }

    public function verifyPayment(Request $request)
    {
        $reference = $request->reference;
        
        if (!$reference) {
            return redirect('/wallet')->with('error', 'No reference supplied');
        }

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        $result = json_decode($response, true);

        if ($result['status'] && $result['data']['status'] == 'success') {
            $user = Auth::user();
            $amount = $result['data']['amount'] / 100;
            
            $exists = $user->transactions()->where('reference', $reference)->exists();
            
            if (!$exists) {
                $wallet = $user->wallet()->firstOrCreate(['user_id' => $user->id]);
                $wallet->increment('balance', $amount);
                
                $user->transactions()->create([
                    'type' => 'credit',
                    'amount' => $amount,
                    'description' => 'Wallet Funding via Paystack',
                    'reference' => $reference,
                    'status' => 'success'
                ]);

                return redirect('/wallet')->with('success', "₦{$amount} credited successfully");
            }
            
            return redirect('/wallet')->with('info', 'Transaction already processed');
        }

        return redirect('/wallet')->with('error', 'Payment verification failed');
    }
}
