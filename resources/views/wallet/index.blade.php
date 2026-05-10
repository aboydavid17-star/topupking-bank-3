@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Wallet Dashboard</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Wallet Balance</h5>
                            <h3 class="text-success">₦{{ number_format($wallet->wallet_balance, 2) }}</h3>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="{{ route('wallet.fund') }}" class="btn btn-primary">Fund Wallet</a>
                            <a href="{{ route('wallet.force') }}" class="btn btn-success">Test Credit ₦600</a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">Transaction History</div>
                        <div class="card-body">
                            @if($transactions->count() > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ ucfirst($transaction->type) }}</td>
                                            <td>₦{{ number_format($transaction->amount, 2) }}</td>
                                            <td>{{ $transaction->description }}</td>
                                            <td>{{ $transaction->status }}</td>
                                            <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No transactions yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
