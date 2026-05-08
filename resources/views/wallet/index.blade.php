<!DOCTYPE html>
<html>
<head>
    <title>TopupKing Wallet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; background: #f0f2f5; margin: 0; padding: 20px; }
       .container { max-width: 600px; margin: 0 auto; }
       .card { background: white; padding: 25px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
       .balance-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
       .balance { font-size: 36px; margin: 10px 0; font-weight: bold; }
       .btn { display: block; width: 100%; padding: 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 8px; text-align: center; margin: 10px 0; border: none; font-size: 16px; cursor: pointer; }
       .btn-logout { background: #f44336; }
       .btn-data { background: #2196F3; }
       .btn-airtime { background: #FF9800; }
       .btn-bills { background: #9C27B0; }
       .transaction { padding: 15px; border-bottom: 1px solid #eee; }
       .success { color: green; background: #e8f5e9; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
       .error { color: red; background: #ffebee; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
       .status-success { color: green; font-weight: bold; }
       .status-pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        @if(session('success'))<div class="success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="error">{{ session('error') }}</div>@endif

        <div class="card balance-card">
            <p>Welcome, {{ $user->name }}</p>
            <p style="font-size: 14px; opacity: 0.9;">Available Balance</p>
            <div class="balance">₦{{ number_format($user->balance?? 0, 2) }}</div>
        </div>

        <div class="card">
            <h3>Quick Actions</h3>
            <a href="{{ route('buy.data') }}" class="btn btn-data">📱 Buy Data</a>
            <a href="#" class="btn btn-airtime">📞 Buy Airtime</a>
            <a href="#" class="btn btn-bills">💡 Pay Bills</a>
        </div>

        <div class="card">
            <h3>Recent Transactions</h3>
            @forelse($transactions as $tx)
                <div class="transaction">
                    <strong>{{ ucfirst($tx->type) }} - {{ $tx->network }}</strong><br>
                    <small>{{ $tx->phone_number }} | ₦{{ number_format($tx->amount, 2) }} | <span class="status-{{ $tx->status }}">{{ ucfirst($tx->status) }}</span></small><br>
                    <small style="color: #666;">{{ $tx->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p style="text-align: center; color: #666;">No transactions yet. Start by buying data!</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-logout">Logout</button>
        </form>
    </div>
</body>
</html>
