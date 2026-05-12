@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">Buy Airtime</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('airtime.buy') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Network</label>
                            <select name="network" class="form-control" required>
                                <option value="">Select Network</option>
                                <option value="mtn">MTN</option>
                                <option value="glo">GLO</option>
                                <option value="airtel">AIRTEL</option>
                                <option value="9mobile">9MOBILE</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="08012345678" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount (₦)</label>
                            <input type="number" name="amount" class="form-control" min="50" max="10000" value="50" required>
                            <small>Min: ₦50 | Max: ₦10,000</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Buy Airtime Now</button>
                        <a href="/dashboard" class="btn btn-secondary w-100 mt-2">Back to Dashboard</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
