<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Balance dey inside users table, not wallets table
        $balance = $user->balance ?? 0.00;

        return "<html>
        <head>
            <title>My Wallet</title>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
        </head>
        <body style='font-family: Arial; padding: 20px; background: #f5f5f5;'>
            <div style='max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                <h1 style='color: #333; margin-bottom: 10px;'>💰 My Wallet</h1>
                <hr style='margin: 20px 0;'>
                <h2 style='color: #555;'>Welcome, {$user->name}</h2>
                <p style='color: #777;'><strong>Email:</strong> {$user->email}</p>
                <div style='background: #4CAF50; color: white; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 0; font-size: 14px;'>Available Balance</p>
                    <h1 style='margin: 10px 0 0 0; font-size: 36px;'>₦". number_format($balance, 2) ."</h1>
                </div>
                <form method='POST' action='/logout' style='margin-top: 30px;'>
                    <input type='hidden' name='_token' value='". csrf_token()."'>
                    <button type='submit' style='width: 100%; padding: 12px; background: #f44336; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;'>Logout</button>
                </form>
            </div>
        </body>
        </html>";
    }
}
