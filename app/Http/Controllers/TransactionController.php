<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->transactions();

        // Filter by type
        if ($request->type && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->status && in_array($request->status, ['successful', 'pending', 'failed'])) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(15)->withQueryString();
        
        return view('transactions.index', compact('transactions'));
    }
}
