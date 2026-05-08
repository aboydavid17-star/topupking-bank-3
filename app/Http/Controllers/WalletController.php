<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()->take(5)->get();

        return view('wallet.index', compact('user', 'transactions'));
    }

    public function buyDataPage()
    {
        return view('wallet.buy-data');
    }

    public function buyData(Request $request)
    {
        $request->validate([
            'network' => 'required|in:MTN,GLO,AIRTEL,9MOBILE',
            'phone' => 'required|string|min:11|max:11',
            'plan' => 'required',
        ]);

        $user = Auth::user();

        $planParts = explode(' - ₦', $request->plan);
        $amount = count($planParts) > 1? (float) str_replace(',', '', $planParts[1]) : 300;
        $balance = $user->balance?? 0;

        if ($balance < $amount) {
            return back()->with('error', 'Insufficient balance. Fund your wallet first.');
        }

        $user->balance = $balance - $amount;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'data',
            'network' => $request->network,
            'phone_number' => $request->phone,
            'plan_name' => $request->plan,
            'amount' => $amount,
            'balance_before' => $balance,
            'balance_after' => $user->balance,
            'status' => 'success',
            'reference' => 'TOPUP_'. Str::upper(Str::random(10)),
        ]);

        return back()->with('success', 'Data purchase successful! '. $request->plan. ' sent to '. $request->phone);
    }

    public function fundWalletPage()
    {
        return view('wallet.fund');
    }

    public function fundWallet(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:100']);

        $user = Auth::user();
        $amount = $request->amount;
        $reference = 'FUND_'. Str::upper(Str::random(10));

        $balanceBefore = $user->balance?? 0;
        $user->balance = $balanceBefore + $amount;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'funding',
            'network' => 'Paystack',
            'phone_number' => $user->email,
            'plan_name' => 'Wallet Funding',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $user->balance,
            'status' => 'success',
            'reference' => $reference,
        ]);

        return back()->with('success', 'Wallet funded successfully! ₦'. number_format($amount, 2). ' added.');
    }
}
