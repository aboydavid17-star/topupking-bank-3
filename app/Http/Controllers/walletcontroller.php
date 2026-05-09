<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Unicodeveloper\Paystack\Facades\Paystack;

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

        return view('wallet.pay', [
            'amount' => $request->amount * 100,
            'email' => Auth::user()->email,
            'reference' => Paystack::genTranxRef()
        ]);
    }

    public function redirectToGateway(Request $request)
    {
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch(\Exception $e) {
            return back()->withErrors(['paystack' => $e->getMessage()]);
        }
    }

    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        if($paymentDetails['data']['status'] == 'success') {
            $user = Auth::user();
            $amount = $paymentDetails['data']['amount'] / 100;
            $user->increment('balance', $amount);
            return redirect()->route('wallet')->with('success', "Wallet funded with ₦{$amount} successfully!");
        }
        
        return redirect()->route('wallet')->withErrors(['payment' => 'Payment failed. Try again.']);
    }

    public function showDataForm()
    {
        $response = Http::withBasicAuth(
            config('services.vtpass.username'), 
            config('services.vtpass.password')
        )->get(config('services.vtpass.url') . '/service-variations?serviceID=mtn-data');
        
        $plans = $response->json()['content']['variations'] ?? [];
        return view('wallet.data', compact('plans'));
    }

    public function buyData(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:11',
            'variation_code' => 'required',
            'amount' => 'required|numeric',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        
        if($user->balance < $amount) {
            return back()->withErrors(['balance' => 'Insufficient wallet balance']);
        }

        $request_id = Str::uuid();
        
        $response = Http::withHeaders([
            'api-key' => config('services.vtpass.api_key'),
            'secret-key' => config('services.vtpass.secret_key'),
        ])->post(config('services.vtpass.url') . '/pay', [
            'request_id' => $request_id,
            'serviceID' => 'mtn-data',
            'billersCode' => $request->phone,
            'variation_code' => $request->variation_code,
            'amount' => $amount,
            'phone' => $request->phone,
        ]);

        if($response->json()['code'] == '000') {
            $user->decrement('balance', $amount);
            return redirect()->route('wallet')->with('success', 'Data purchase successful!');
        }

        return back()->withErrors(['vtpass' => $response->json()['response_description'] ?? 'Transaction failed']);
    }

    public function showAirtimeForm()
    {
        return view('wallet.airtime');
    }

    public function buyAirtime(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:11',
            'amount' => 'required|numeric|min:50|max:5000',
            'network' => 'required|in:mtn,glo,airtel,9mobile'
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        
        if($user->balance < $amount) {
            return back()->withErrors(['balance' => 'Insufficient wallet balance']);
        }

        $request_id = Str::uuid();
        
        $response = Http::withHeaders([
            'api-key' => config('services.vtpass.api_key'),
            'secret-key' => config('services.vtpass.secret_key'),
        ])->post(config('services.vtpass.url') . '/pay', [
            'request_id' => $request_id,
            'serviceID' => $request->network,
            'amount' => $amount,
            'phone' => $request->phone,
        ]);

        if($response->json()['code'] == '000') {
            $user->decrement('balance', $amount);
            return redirect()->route('wallet')->with('success', 'Airtime purchase successful!');
        }

        return back()->withErrors(['vtpass' => $response->json()['response_description'] ?? 'Transaction failed']);
    }
}
