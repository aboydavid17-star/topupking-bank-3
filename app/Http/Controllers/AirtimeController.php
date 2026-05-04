<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class AirtimeController extends Controller
{
    public function index()
    {
        return view('user.airtime');
    }

    public function buy(Request $request)
    {
        $request->validate([
            'network' => 'required',
            'phone' => 'required|digits:11',
            'amount' => 'required|numeric|min:50',
        ]);

        $user = Auth::user();
        
        // ===== PUT YOUR CLUBKONNECT DETAILS HERE =====
        $userid = CK101277966
$apikey = NXQ0YU65L0498M0PZ6V3D8URV46EAIW3555576G1PHT1B6S5KVA15N8635ITB5P6
        
        // =============================================

        $network = $request->network;
        $phone = $request->phone;
        $amount = $request->amount;
        $requestID = time(). rand(1000,9999);

        if ($user->wallet_balance < $amount) {
            return back()->with('error', 'Insufficient wallet balance. Fund wallet first.');
        }

        $response = Http::get("https://www.clubkonnect.com/api/airtime/", [
            'userid' => $userid,
            'apikey' => $apikey,
            'network' => $network,
            'phone' => $phone,
            'amount' => $amount,
            'requestID' => $requestID,
        ]);

        if ($response->successful() && str_contains($response->body(), 'SUCCESSFUL')) {
            $user->wallet_balance -= $amount;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'airtime',
                'amount' => $amount,
                'status' => 'success',
                'details' => "Airtime to $phone",
                'reference' => $requestID,
            ]);

            return back()->with('success', "Airtime sent to $phone successfully!");
        } else {
            return back()->with('error', 'Airtime purchase failed: '. $response->body());
        }
    }
}
