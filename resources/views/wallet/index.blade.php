@extends('layouts.app')

@section('content')
<div class="container">
    <div class="wallet-header">
        <h1>TopupKing 👑</h1>
        <div class="user-info">
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="card balance-card">
        <p class="label">Wallet Balance</p>
        <h2 class="balance">₦{{ number_format($wallet->balance ?? 0, 2) }}</h2>
    </div>

    <div class="card">
        <h3>Quick Actions</h3>
        
        <!-- FIXED: Changed wallet.fund to fund.wallet -->
        <a href="{{ route('fund.wallet') }}" class="btn btn-primary">
            💰 Fund Wallet
        </a>
        
        <!-- FIXED: Changed wallet.buy-data to buy.data -->
        <a href="{{ route('buy.data') }}" class="btn btn-success">
            📱 Buy Data
        </a>
        
        <!-- FIXED: Changed wallet.buy-airtime to buy.airtime -->
        <a href="{{ route('buy.airtime') }}" class="btn btn-secondary">
            📞 Buy Airtime
        </a>
        
        <!-- FIXED: Changed wallet.cable to cable -->
        <a href="{{ route('cable') }}" class="btn btn-secondary">
            📺 Cable TV
        </a>
    </div>

    <div class="card">
        <h3>Recent Transactions</h3>
        @if($transactions->count() > 0)
            @foreach($transactions as $transaction)
                <div class="transaction">
                    <span>{{ $transaction->type }}</span>
                    <span>₦{{ number_format($transaction->amount, 2) }}</span>
                </div>
            @endforeach
        @else
            <p class="alert-info">No transactions yet. Fund your wallet to get started!</p>
        @endif
    </div>
</div>

<style>
.container { max-width: 600px; margin: 0 auto; padding: 20px; }
.wallet-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
.balance-card { text-align: center; }
.balance { font-size: 48px; color: #10b981; margin: 10px 0; }
.btn { display: block; width: 100%; padding: 15px; margin: 10px 0; border: none; border-radius: 8px; font-size: 16px; text-decoration: none; text-align: center; cursor: pointer; }
.btn-primary { background: #3b82f6; color: white; }
.btn-success { background: #10b981; color: white; }
.btn-secondary { background: #6b7280; color: white; }
.btn-logout { background: rgba(255,255,255,0.2); color: white; border: 1px solid white; padding: 8px 16px; border-radius: 6px; cursor: pointer; }
.label { color: #6b7280; margin: 0; }
.alert-info { background: #dbeafe; color: #1e40af; padding: 12px; border-radius: 6px; }
.transaction { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
</style>
@endsection
