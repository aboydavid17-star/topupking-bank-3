<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use App\Models\Transaction;

class WalletController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the wallet dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // AUTO CREATE WALLET IF NO EXIST - FIXES "Undefined variable $wallet"
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00]
        );

        // Get last 10 transactions
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('wallet.index', compact('wallet', 'transactions'));
    }

    /**
     * Fund wallet - for testing only
     */
    public function fund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:50000'
        ]);

        $user = Auth::user();
        
        // AUTO CREATE WALLET IF NO EXIST
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00]
        );

        DB::beginTransaction();
        try {
            // Update balance
            $wallet->balance += $request->amount;
            $wallet->save();

            // Log transaction
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $request->amount,
                'description' => 'Wallet funding',
                'status' => 'successful',
                'reference' => 'WALLET-' . time() . '-' . $user->id
            ]);

            DB::commit();

            return redirect()->route('wallet.index')->with('success', 'Wallet funded with ₦' . number_format($request->amount) . ' successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('wallet.index')->with('error', 'Funding failed. Please try again.');
        }
    }

    /**
     * Show fund wallet form
     */
    public function showFundForm()
    {
        $user = Auth::user();
        
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00]
        );

        return view('wallet.fund', compact('wallet'));
    }
}
