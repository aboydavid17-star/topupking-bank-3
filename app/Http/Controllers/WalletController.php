<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class WalletController extends Controller
{
    /**
     * Show wallet dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // If you have a wallets table, use this:
        // $balance = $user->wallet->balance ?? 0;
        
        // If you store balance on users table, use this:
        $balance = $user->balance ?? 0;
        
        return view('wallet.index', [
            'balance' => $balance,
            'user' => $user
        ]);
    }

    /**
     * Show fund wallet form
     */
    public function showFundForm()
    {
        return view('wallet.fund');
    }

    /**
     * Handle fund wallet POST
     */
    public function fund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:100000'
        ]);

        $amount = $request->amount;
        
        // TODO: Add Paystack payment logic here
        return redirect()->route('wallet')->with('success', "Ready to fund ₦{$amount}. Paystack integration next.");
    }

    /**
     * Show buy data form
     */
    public function showDataForm()
    {
        return view('wallet.data');
    }

    /**
     * Handle buy data POST
     */
    public function buyData(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:11',
            'plan' => 'required'
        ]);

        // TODO: Add
