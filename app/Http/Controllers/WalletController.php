<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $balance = $user->balance ?? 0;
        
        return view('wallet.index', compact('user', 'balance'));
    }

    public function showFundForm()
    {
        return view('wallet.fund');
    }

    public function fund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:50000'
        ]);

        $amount = $request->amount;
        return back()->with('success', "Ready to fund ₦{$amount}. Paystack integration next.");
    }

    public function showDataForm()
    {
        return view('wallet.data');
    }

    public function buyData(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:11',
            'plan' => 'required'
        ]);

        return back()->with('success', 'Data purchase coming soon.');
    }

    public function showAirtimeForm()
    {
        return view('wallet.airtime');
    }

    public function buyAirtime(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:11',
            'amount' => 'required|numeric|min:50'
        ]);

        return back()->with('success', 'Airtime purchase coming soon.');
    }
}
