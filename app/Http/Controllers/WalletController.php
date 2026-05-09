<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show wallet dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        // Auto-create wallet if missing
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0.00,
            ]);
        }

        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('wallet.index', compact('wallet', 'transactions'));
    }

    /**
     * Show fund wallet page
     */
    public function showFund()
    {
        $wallet = Auth::user()->wallet;
        return view('wallet.fund', compact('wallet'));
    }

    /**
     * Fund wallet - Paystack coming soon
     */
    public function fundWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:50000',
        ]);

        // TODO: Add Paystack integration here
        
        return back()->with('success', 'Funding successful! Paystack integration coming next.');
    }

    /**
     * Show buy data page - THIS FIXES YOUR ERROR
     */
    public function showBuyData()
    {
        $wallet = Auth::user()->wallet;
        return view('wallet.buy-data', compact('wallet'));
    }

    /**
     * Buy data - VTPass coming soon
     */
    public function buyData(Request $request)
    {
        $request->validate([
            'network' => 'required|string',
            'phone' => 'required|string|min:11|max:11',
            'plan' => 'required|string',
        ]);

        // TODO: Add VTPass API integration here
        
        return back()->with('success', 'Data purchase successful! VTPass integration coming next.');
    }

    /**
     * Show all transactions
     */
    public function transactions()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('wallet.transactions', compact('transactions'));
    }
}
