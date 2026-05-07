<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Display the user's wallet page.
     */
    public function index()
    {
        // Example: get the logged in user's wallet data
        $user = Auth::user();
        
        // Replace this with your actual wallet logic
        $balance = $user->wallet_balance ?? 0;
        $transactions = $user->transactions()->latest()->take(10)->get();

        return view('wallet.index', [
            'balance' => $balance,
            'transactions' => $transactions,
            'user' => $user
        ]);
    }

    /**
     * Show the form for funding wallet.
     */
    public function create()
    {
        return view('wallet.create');
    }

    /**
     * Store a new wallet funding request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        // Add your payment logic here
        
        return redirect()->route('wallet.index')->with('success', 'Wallet funded successfully');
    }
}
