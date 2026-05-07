<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = Transaction::where('user_id', $user->id)
                        ->latest()
                        ->take(10)
                        ->get();
                        
        return view('wallet.index', [
            'balance' => $user->wallet_balance ?? 0,
            'user' => $user,
            'transactions' => $transactions
        ]);
    }

    public function create()
    {
        return view('wallet.create');
    }

    public function verify($reference)
    {
        $secretKey = env('PAYSTACK_SECRET_KEY');
        
        $response = Http::withToken($secretKey)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        $result = $response->json();

        if ($result['status'] && $result['data']['status'] === 'success') {
            $amount = $result['data']['amount'] / 100; // Convert from kobo
            $user = auth()->user();
            
            // Update wallet balance
            $user->wallet_balance = ($user->wallet_balance ?? 0) + $amount;
            $user->save();

            // Save transaction record
            Transaction::create([
                'user_id' => $user->id,
                'reference' => $reference,
                'amount' => $amount,
                'type' => 'credit',
                'status' => 'success',
                'description' => 'Wallet funding via Paystack'
            ]);

            return redirect()->route('wallet.index')
                ->with('success', 'Wallet funded successfully! ₦' . number_format($amount, 2) . ' added');
        }

        return redirect()->route('wallet.index')
            ->with('error', 'Payment verification failed');
    }
}
