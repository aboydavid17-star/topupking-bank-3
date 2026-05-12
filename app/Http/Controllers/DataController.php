<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class DataController extends Controller
{
    public function index()
    {
        return view('user.data');
    }

    public function buy(Request $request)
    {
        $request->validate([
            'network' => 'required',
            'phone' => 'required|digits:11',
            'dataplan' => 'required',
        ]);

        $user = Auth::user();

        // ===== PUT YOUR SAME CLUBKONNECT DETAILS HERE =====
        $userid = CK101277966
$apikey = NXQ0YU65L0498M0PZ6V3D8URV46EAIW3555576G1PHT1B6S5KVA15N8635ITB5P6
        // ===================================================

        $network = $request->network;
        $phone = $request->phone;
        $dataplan = $request->dataplan;
        $planDetails = explode('|', $dataplan);
        $planCode = $planDetails[0];
        $amount = $planDetails[1];

        $requestID = time(). rand(1000,9999);

        if ($user->wallet_balance < $amount) {
            return back()->with('error', 'Insufficient wallet balance. Fund wallet first.');
        }

        $response = Http::get("https://www.clubkonnect.com/api/data/", [
            'userid' => $userid,
            'apikey' => $apikey,
            'network' => $network,
            'phone' => $phone,
            'dataplan' => $planCode,
            'requestID' => $requestID,
        ]);

        if ($response->successful() && str_contains($response->body(), 'SUCCESSFUL')) {
            $user->wallet_balance -= $amount;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'data',
                'amount' => $amount,
                'status' => 'success',
                'details' => "Data bundle to $phone",
                'reference' => $requestID,
            ]);

            return back()->with('success', "Data bundle sent to $phone successfully!");
        } else {
            return back()->with('error', 'Data purchase failed: '. $response->body());
        }
    }
}
