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

    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
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

    public function showFund()
    {
        $wallet = Auth::user()->wallet;
        return view('wallet.fund', compact('wallet'));
    }

    public function fundWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:50000',
        ]);

        // TODO: Add Paystack here
        return back()->with('success', 'Paystack integration coming next!');
    }

    public function showBuyData()
    {
        $wallet = Auth::user()->wallet;
        return view('wallet.buy-data', compact('wallet'));
    }

    public function buyData(Request $request)
    {
        $request->validate([
            'network' => 'required|string',
            'phone' => 'required|string|min:11|max:11',
            'plan' => 'required|string',
        ]);

        // TODO: Add VTPass here
        return back()->with('success', 'VTPass integration coming next!');
    }

    public function showBuyAirtime()
    {
        $wallet = Auth::user()->wallet;
        return view('wallet.buy-airtime', compact('wallet'));
    }

    public function buyAirtime(Request $request)
    {
        $request->validate([
            'network' => 'required|string',
            'phone' => 'required|string|min:11|max:11',
            'amount' => 'required|numeric|min:50',
        ]);

        // TODO: Add VTPass here
        return back()->with('success', 'Airtime purchase coming next!');
    }

    public function showCable()
    {
        $wallet = Auth::user()->wallet;
        return view('wallet.cable', compact('wallet'));
    }

    public function buyCable(Request $request)
    {
        $request->validate([
            'decoder' => 'required|string',
            'iuc' => 'required|string',
            'plan' => 'required|string',
        ]);

        // TODO: Add VTPass here
        return back()->with('success', 'Cable payment coming next!');
    }

    public function transactions()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('wallet.transactions', compact('transactions'));
    }
}
