@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">Wallet Balance</div>
                <div class="card-body">
                    <h2 class="mb-4">₦{{ number_format($wallet->balance, 2) }}</h2>
                    
                    <form method="POST" action="{{ route('wallet.fund') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Amount</label>
                            <input type="number" name="amount" placeholder="Enter amount" class="form-control" required min="100">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Fund Wallet</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">Recent Transactions</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->reference }}</td>
                                    <td>₦{{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ ucfirst($transaction->type) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No transactions yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
