@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <h5>Welcome, {{ Auth::user()->name }}!</h5>
                    <p><strong>Wallet Balance: ₦{{ Auth::user()->wallet_balance }}</strong></p>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <a href="{{ route('airtime.index') }}" class="btn btn-primary btn-lg w-100 p-3">
                                <h4>📱</h4><strong>Buy Airtime</strong>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('data.index') }}" class="btn btn-success btn-lg w-100 p-3">
                                <h4>📶</h4><strong>Buy Data</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
