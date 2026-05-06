<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    public function showFundForm()
    {
        return view('fund-wallet');
    }

    public function initializePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $amount = $request->amount * 100;

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => auth()->user()->email,
                'amount' => $amount,
                'callback_url' => route('payment.callback'),
                'metadata' => [
                    'user_id' => auth()->user()->id,
                ]
            ]);

        if ($response->successful()) {
            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Payment initialization failed. Try again.');
    }

    public function handleCallback(Request $request)
    {
        $reference = $request->reference;

        if (!$reference) {
            return redirect()->route('dashboard')->with('error', 'No reference supplied');
        }

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful() && $response['data']['status'] == 'success') {
            $amount = $response['data']['amount'] / 100;
            
            $user = auth()->user();
            $user->wallet = ($user->wallet ?? 0) + $amount;
            $user->save();

            return redirect()->route('dashboard')->with('success', "Wallet funded with ₦{$amount}");
        }

        return redirect()->route('dashboard')->with('error', 'Payment verification failed');
    }
}
