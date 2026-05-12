<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = $user->transactions()->latest()->get();
        
        return view('wallet.index', [
            'wallet' => $user,
            'transactions' => $transactions
        ]);
    }

    public function forceCredit()
    {
        $user = auth()->user();
        
        DB::transaction(function () use ($user) {
            // Credit ₦600
            $user->wallet_balance += 600;
            $user->save();

            // Log transaction
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => 600,
                'description' => 'Manual Credit - Paystack Test',
                'status' => 'Success',
                'reference' => 'FORCE_' . time(),
            ]);
        });

        return redirect('/wallet')->with('success', 'Wallet credited with ₦600');
    }

    public function showFundingForm()
    {
        return view('wallet.fund');
    }

    public function fund(Request $request)
    {
        // Paystack logic goes here later
    }

    public function verifyPayment(Request $request)
    {
        // Paystack verify logic goes here later
    }
}
