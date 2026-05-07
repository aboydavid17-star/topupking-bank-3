<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $balance = $user->wallet_balance ?? 0;

        return view('wallet.index', [
            'balance' => $balance,
            'user' => $user
        ]);
    }
}
