<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // AUTO CREATE WALLET IF NO EXIST - THIS FIXES THE ERROR
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00]
        );

        return view('wallet.index', compact('wallet'));
    }

    public function fund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $wallet = Wallet::firstOrCreate(
            ['user_id' => Auth::id()],
            ['balance' => 0.00]
        );

        $wallet->balance += $request->amount;
        $wallet->save();

        return redirect()->route('wallet.index')->with('success', 'Wallet funded successfully!');
    }
}
