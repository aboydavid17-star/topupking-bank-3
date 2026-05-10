@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Fund Wallet</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('fund') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₦)</label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" value="{{ old('amount') }}" 
                                   min="100" max="50000" placeholder="Enter amount" required>
                            @error('amount')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Minimum: ₦100, Maximum: ₦50,000</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Pay with Paystack
                        </button>
                        
                        <a href="{{ route('wallet.index') }}" class="btn btn-secondary w-100 mt-2">
                            Back to Wallet
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
