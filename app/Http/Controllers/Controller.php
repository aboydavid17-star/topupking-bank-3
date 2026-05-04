<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Create wallet if e no exist
        if (!$user->wallet) {
            $user->wallet()->create(['balance' => 0]);
        }
        
        $balance = $user->wallet->balance;
        
        return view('dashboard', ['balance' => $balance]);
    }
}
