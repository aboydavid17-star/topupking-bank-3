<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return "<html>
        <head><title>Wallet</title></head>
        <body style='font-family: Arial; padding: 40px;'>
            <h1>🎉 Wallet Page Working!</h1>
            <h2>Welcome: {$user->name}</h2>
            <p><strong>Email:</strong> {$user->email}</p>
            <p><strong>Balance:</strong> ₦0.00</p>
            <br>
            <form method='POST' action='/logout'>
                <input type='hidden' name='_token' value='". csrf_token()."'>
                <button type='submit' style='padding: 10px 20px; background: red; color: white; border: none; border-radius: 5px;'>Logout</button>
            </form>
        </body>
        </html>";
    }
}
