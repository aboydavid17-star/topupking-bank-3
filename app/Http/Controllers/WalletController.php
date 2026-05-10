<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('wallet.index', compact('user'));
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

        $amount = $request->amount * 100;
        $reference = 'TKB_' . uniqid() . time();

        $data = [
            "amount" => $amount,
            "email" => auth()->user()->email,
            "reference" => $reference,
            "callback_url" => route('paystack.callback')
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "Content-Type: application/json"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return back()->with('error', 'cURL Error: ' . $err);
        }

        $result = json_decode($response, true);

        if ($result['status']) {
            return redirect($result['data']['authorization_url']);
        }

        return back()->with('error', 'Payment initialization failed');
    }

    public function handleGatewayCallback()
    {
        $reference = request()->query('reference');
        
        if (empty($reference)) {
            return redirect()->route('wallet.index')->with('error', 'No transaction reference supplied');
        }

        $secretKey = env('PAYSTACK_SECRET_KEY');
        $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);
        
        $ch = curl_init();
