<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class WalletController extends Controller
{
    /**
     * Show the fund wallet form
     */
    public function showFundForm()
    {
        return view('fund-wallet');
    }

    /**
     * Initialize Paystack payment
     */
    public function initializePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:1000000'
        ]);

        $user = Auth::user();
        $amount = $request->amount * 100; // Paystack uses kobo

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $user->email,
                'amount' => $amount,
                'callback_url' => route('payment.callback'),
                'metadata' => [
                    'user_id' => $user->id,
                    'purpose' => 'wallet_funding',
                    'custom_fields' => [
                        [
                            'display_name' => 'User ID',
                            'variable_name' => 'user_id',
                            'value' => $user->id
                        ]
                    ]
                ]
            ]);

        if ($response->successful() && isset($response['data']['authorization_url'])) {
            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Unable to initialize payment. Please try again.');
    }

    /**
     * Handle Paystack callback after payment
     */
    public function paymentCallback(Request $request)
    {
        $reference = $request->query('reference');
        
        if (!$reference) {
            return redirect()->route('dashboard')->with('error', 'No transaction reference found');
        }

        // Verify payment with Paystack
        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful()) {
            $data = $response['data'];
            
            if ($data['status'] === 'success') {
                $amount = $data['amount'] / 100; // Convert kobo to naira
                $userId = $data['metadata']['user_id'] ?? Auth::id();
                
                $user = User::find($userId);
                
                if ($user) {
                    $user->wallet += $amount;
                    $user->save();
                    
                    return redirect()->route('dashboard')->with('success', "Wallet funded successfully with ₦" . number_format($amount, 2));
                }
            }
        }

        return redirect()->route('dashboard')->with('error', 'Payment verification failed or was not successful');
    }
}
