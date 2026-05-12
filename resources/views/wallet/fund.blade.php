@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Fund Wallet</div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('fund') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₦)</label>
                            <input type="number" class="form-control" id="amount" name="amount" min="100" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Pay with Paystack</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
