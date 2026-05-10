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
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $secretKey,
            "Cache-Control: no-cache",
        ]);
        
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            Log::error('Paystack cURL Error: ' . $err);
            return redirect()->route('wallet.index')->with('error', 'Payment verification failed');
        }

        if ($httpcode != 200) {
            Log::error('Paystack HTTP Error: ' . $httpcode . ' Response: ' . $response);
            return redirect()->route('wallet.index')->with('error', 'Payment verification failed');
        }

        $result = json_decode($response, true);

        if (!$result['status'] || $result['data']['status'] !== 'success') {
            return redirect()->route('wallet.index')->with('error', 'Payment was not successful');
        }

        $user = auth()->user();
        $amount = $result['data']['amount'] / 100;
        
        $exists = Transaction::where('reference', $reference)->exists();
        if ($exists) {
            return redirect()->route('wallet.index')->with('info', 'Transaction already processed');
        }

        DB::transaction(function () use ($user, $amount, $reference) {
            $balanceBefore = $user->wallet;
            
            $user->increment('wallet', $amount);
            $user->refresh();
            
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'purpose' => 'funding',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $user->wallet,
                'reference' => $reference,
                'status' => 'successful',
                'description' => 'Wallet funding via Paystack'
            ]);
        });

        return redirect()->route('wallet.index')->with('success', 'Wallet funded successfully! ₦' . number_format($amount, 2));
    }
} // <-- THIS CLOSING BRACE WAS MISSING
