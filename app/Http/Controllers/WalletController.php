<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Wallet;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // AUTO-CREATE WALLET IF IT DOESN'T EXIST
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00]
        );
        
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();
        
        return view('wallet.index', compact('wallet', 'transactions'));
    }

    public function fundWalletPage()
    {
        return view('wallet.fund');
    }

    public function fundWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:50000'
        ]);

        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        $wallet->balance += $request->amount;
        $wallet->save();

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => $request->amount,
            'description' => 'Wallet funded',
            'status' => 'completed'
        ]);

        return redirect()->route('wallet')->with('success', 'Wallet funded successfully! ₦' . number_format($request->amount, 2) . ' added.');
    }

    public function buyDataPage()
    {
        return view('wallet.buy-data');
    }

    public function buyData(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:11',
            'network' => 'required|string',
            'plan' => 'required|numeric'
        ]);

        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if ($wallet->balance < $request->plan) {
            return back()->with('error', 'Insufficient balance. Please fund your wallet.');
        }

        // VTPass API Call
        $response = Http::withHeaders([
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://vtpass.com/api/pay', [
            'request_id' => uniqid('topupking_'),
            'serviceID' => $request->network, // mtn-data, glo-data, etc
            'billersCode' => $request->phone,
            'variation_code' => $this->getVariationCode($request->plan),
            'amount' => $request->plan,
            'phone' => $request->phone
        ]);

        if ($response->successful() && $response['code'] == '000') {
            $wallet->balance -= $request->plan;
            $wallet->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $request->plan,
                'description' => 'Data purchase for ' . $request->phone,
                'status' => 'completed'
            ]);

            return redirect()->route('wallet')->with('success', 'Data delivered! ' . $this->getDataSize($request->plan) . ' sent to ' . $request->phone);
        }

        return back()->with('error', 'Data purchase failed. Please try again or contact support.');
    }

    private function getVariationCode($amount)
    {
        // MTN Data Plans - Sandbox
        $plans = [
            300 => 'mtn-10mb-100',   // 1GB
            500 => 'mtn-100mb-200',  // 2GB
            1000 => 'mtn-200mb-300', // 3GB
        ];
        return $plans[$amount] ?? 'mtn-10mb-100';
    }

    private function getDataSize($amount)
    {
        $sizes = [
            300 => '1GB',
            500 => '2GB', 
            1000 => '3GB',
        ];
        return $sizes[$amount] ?? '1GB';
    }
}
