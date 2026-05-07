<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );
        
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('wallet.index', compact('wallet', 'transactions'));
    }

    public function fund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $user = auth()->user();
        $reference = 'TXN_' . Str::random(10);

        Transaction::create([
            'user_id' => $user->id,
            'reference' => $reference,
            'amount' => $request->amount,
            'type' => 'credit',
            'status' => 'pending'
        ]);

        $data = [
            'email' => $user->email,
            'amount' => $request->amount * 100,
            'reference' => $reference,
            'callback_url' => route('wallet.callback')
        ];

        $response = Http::withToken(config('services.paystack.secret_key'))
            ->post('https://api.paystack.co/transaction/initialize', $data);

        if($response->successful()) {
            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Payment initialization failed');
    }

    public function callback(Request $request)
    {
        $reference = $request->reference;
        
        $response = Http::withToken(config('services.paystack.secret_key'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if($response->successful() && $response['data']['status'] == 'success') {
            $transaction = Transaction::where('reference', $reference)->first();
            
            if($transaction && $transaction->status == 'pending') {
                $transaction->update(['status' => 'completed']);
                
                $wallet = Wallet::where('user_id', $transaction->user_id)->first();
                $wallet->increment('balance', $transaction->amount);
                
                return redirect()->route('wallet.index')->with('success', 'Wallet funded successfully!');
            }
        }

        return redirect()->route('wallet.index')->with('error', 'Payment verification failed');
    }
}
