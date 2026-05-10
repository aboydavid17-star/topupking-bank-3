<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;
use Illuminate\Support\Str;

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
            'balance' => $user->wallet_balance,
            'transactions' => $transactions
        ]);
    }

    public function showFundingForm()
    {
        return view('wallet.fund');
    }

    public function fund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $amount = $request->amount * 100; // Convert to kobo
        $reference = 'TOPUP_' . Str::random(10) . '_' . time();
        
        // Save pending transaction
        Transaction::create([
            'user_id' => auth()->id(),
            'type' => 'credit',
            'amount' => $request->amount,
            'description' => 'Wallet Funding via Paystack',
            'status' => 'pending',
            'reference' => $reference,
        ]);

        // Initialize Paystack payment
        $response = Http::withToken(config('services.paystack.secret_key'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => auth()->user()->email,
                'amount' => $amount,
                'reference' => $reference,
                'callback_url' => route('wallet.verify'),
            ]);

        if ($response->successful() && $response['status']) {
            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Unable to initialize payment. Try again.');
    }

    public function verifyPayment(Request $request)
    {
        $reference = $request->reference;
        
        if (!$reference) {
            return redirect()->route('wallet.index')->with('error', 'No reference supplied');
        }

        $response = Http::withToken(config('services.paystack.secret_key'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        $transaction = Transaction::where('reference', $reference)->first();

        if ($response->successful() && $response['data']['status'] === 'success') {
            if ($transaction && $transaction->status === 'pending') {
                $user = $transaction->user;
                $user->wallet_balance += $transaction->amount;
                $user->save();

                $transaction->update(['status' => 'success']);
                
                return redirect()->route('wallet.index')->with('success', 'Wallet funded successfully!');
            }
        }

        if ($transaction) {
            $transaction->update(['status' => 'failed']);
        }

        return redirect()->route('wallet.index')->with('error', 'Payment verification failed');
    }

    public function forceCredit()
    {
        $user = auth()->user();
        
        // Credit ₦600
        $user->wallet_balance += 600;
        $user->save();
        
        // Log transaction
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 600,
            'description' => 'Manual Credit - Paystack Test',
            'status' => 'success',
            'reference' => 'TEST_' . time(),
        ]);
        
        return redirect()->route('wallet.index')->with('success', 'Wallet credited with ₦600');
    }
}
