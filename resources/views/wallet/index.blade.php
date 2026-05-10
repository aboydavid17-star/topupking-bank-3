@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Transaction History</h2>
        <a href="{{ route('wallet.index') }}" class="btn btn-primary">Back to Wallet</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    <tr>
                        <td>{{ $txn->created_at->format('M d, Y h:i A') }}</td>
                        <td><code>{{ $txn->reference }}</code></td>
                        <td>{{ $txn->description ?? ucfirst($txn->purpose) }}</td>
                        <td>
                            @if($txn->type == 'credit')
                                <span class="badge bg-success">Credit</span>
                            @else
                                <span class="badge bg-danger">Debit</span>
                            @endif
                        </td>
                        <td class="fw-bold">₦{{ number_format($txn->amount, 2) }}</td>
                        <td>₦{{ number_format($txn->balance_after, 2) }}</td>
                        <td>
                            @if($txn->status == 'successful')
                                <span class="badge bg-success">Successful</span>
                            @elseif($txn->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No transactions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="card-footer">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
