<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showFundForm()
    {
        return view('fund-wallet');
    }

    public function initialize(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $amount = $request->amount * 100;
        $email = Auth::user()->email;
        $reference = 'TK' . time() . rand(1000, 9999);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $email,
            'amount' => $amount,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
        ]);

        if ($response->successful()) {
            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Payment initialization failed');
    }

    public function callback(Request $request)
    {
        $reference = $request->reference;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful() && $response['data']['status'] == 'success') {
            $amount = $response['data']['amount'] / 100;
            
            $user = Auth::user();
            $user->wallet = $user->wallet + $amount;
            $user->save();

            return redirect('/dashboard')->with('success', "Wallet credited with ₦{$amount}");
        }

        return redirect('/dashboard')->with('error', 'Payment failed');
    }
}
