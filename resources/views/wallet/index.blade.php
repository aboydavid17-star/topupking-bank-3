@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Fund Wallet</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Hello, {{ $user->name }}</strong><br>
                        Current Balance: ₦{{ number_format($balance, 2) }}
                    </div>
                    <form method="POST" action="{{ route('fund-wallet.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Enter Amount (₦)</label>
                            <input type="number" name="amount" class="form-control" min="100" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Fund Wallet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
