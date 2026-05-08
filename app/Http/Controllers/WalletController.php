<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

        // Extract amount and variation_code from plan
        $planMap = [
            '1GB - 30 Days - ₦300' => ['amount' => 300, 'code' => 'mtn-10mb-100'],
            '2GB - 30 Days - ₦600' => ['amount' => 600, 'code' => 'mtn-250mb-300'],
            '5GB - 30 Days - ₦1,500' => ['amount' => 1500, 'code' => 'mtn-1gb-500'],
            '10GB - 30 Days - ₦3,000' => ['amount' => 3000, 'code' => 'mtn-2gb-1000'],
        ];

        $planDetails = $planMap[$request->plan]?? ['amount' => 300, 'code' => 'mtn-10mb-100'];
        $amount = $planDetails['amount'];
        $variationCode = $planDetails['code'];
        $balance = $user->balance?? 0;

        if ($balance < $amount) {
            return back()->with('error', 'Insufficient balance. Fund your wallet first.');
        }

        $requestId = date('YmdHis'). Str::random(5);
        
        // Call VTPass API
        $response = Http::withHeaders([
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
        ])->post('https://sandbox.vtpass.com/api/pay', [
            'request_id' => $requestId,
            'serviceID' => strtolower($request->network). '-data',
            'billersCode' => $request->phone,
            'variation_code' => $variationCode,
            'amount' => $amount,
            'phone' => $request->phone,
        ]);

        $result = $response->json();
        $status = 'failed';
        $apiResponse = json_encode($result);

        if ($response->successful() && isset($result['code']) && $result['code'] === '000') {
            $status = 'success';
            $user->balance = $balance - $amount;
            $user->save();
        }

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'data',
            'network' => $request->network,
            'phone_number' => $request->phone,
            'plan_name' => $request->plan,
            'amount' => $amount,
            'balance_before' => $balance,
            'balance_after' => $user->balance,
            'status' => $status,
            'api_response' => $apiResponse,
            'reference' => $requestId,
        ]);

        if ($status === 'success') {
            return back()->with('success', 'Data delivered! '. $request->plan. ' sent to '. $request->phone);
        } else {
            $errorMsg = $result['response_description']?? 'VTPass API error. Try again.';
            return back()->with('error', 'Transaction failed: '. $errorMsg);
        }
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
