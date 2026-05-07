@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('My Wallet') }}</div>

                <div class="card-body">
                    {{-- Success Message --}}
                    @if(session('success'))
                        <div style="background:#d4edda; color:#155724; padding:12px; border-radius:8px; margin-bottom:20px; border:1px solid #c3e6cb;">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if(session('error'))
                        <div style="background:#f8d7da; color:#721c24; padding:12px; border-radius:8px; margin-bottom:20px; border:1px solid #f5c6cb;">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Wallet Balance --}}
                    <div style="text-align:center; margin-bottom:30px;">
                        <p style="font-size:14px; color:#666; margin-bottom:5px;">Wallet Balance</p>
                        <h1 style="font-size:48px; font-weight:700; color:#10B981; margin:0;">
                            ₦{{ number_format($balance, 2) }}
                        </h1>
                    </div>

                    {{-- Fund Wallet Button --}}
                    <div style="text-align:center; margin-bottom:20px;">
                        <a href="{{ route('wallet.create') }}" 
                           style="background:#10B981; color:white; padding:12px 30px; border-radius:8px; text-decoration:none; display:inline-block; font-weight:600;">
                            Fund Wallet
                        </a>
                    </div>

                    {{-- Quick Info --}}
                    <div style="background:#f9f9f9; padding:15px; border-radius:8px; font-size:14px; color:#555;">
                        <p style="margin:5px 0;"><strong>Account Name:</strong> {{ $user->name }}</p>
                        <p style="margin:5px 0;"><strong>Email:</strong> {{ $user->email }}</p>
                        <p style="margin:5px 0;"><strong>Minimum Funding:</strong> ₦100</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
