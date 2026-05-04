@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Buy Airtime</div>
                <div class="card-body">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('airtime.buy') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label>Network</label>
                            <select name="network" class="form-control" required>
                                <option value="">Select Network</option>
                                <option value="1">MTN</option>
                                <option value="2">GLO</option>
                                <option value="3">AIRTEL</option>
                                <option value="4">9MOBILE</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="08012345678" required>
                        </div>

                        <div class="mb-3">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control" placeholder="100" min="50" required>
                            <small>Wallet Balance: ₦{{ Auth::user()->wallet_balance }}</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Buy Airtime Now</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
