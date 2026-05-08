<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet - TopupKing</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:Arial,sans-serif;background:#f0f2f5;min-height:100vh}
        .navbar{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:15px 20px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 10px rgba(0,0,0,0.1)}
        .navbar h1{font-size:22px}
        .navbar .user{display:flex;align-items:center;gap:15px}
        .navbar a{color:white;text-decoration:none;background:rgba(255,255,255,0.2);padding:8px 15px;border-radius:5px;font-size:14px}
        .navbar a:hover{background:rgba(255,255,255,0.3)}
        .container{max-width:600px;margin:30px auto;padding:0 20px}
        .card{background:white;border-radius:15px;padding:25px;margin-bottom:20px;box-shadow:0 2px 10px rgba(0,0,0,0.08)}
        .balance{text-align:center;padding:20px 0}
        .balance h2{color:#666;font-size:16px;margin-bottom:10px;font-weight:normal}
        .balance .amount{font-size:48px;font-weight:bold;color:#28a745;margin:10px 0}
        .balance .currency{font-size:24px;color:#666}
        h3{color:#333;margin-bottom:15px;font-size:18px}
        .btn{display:block;width:100%;padding:14px;text-align:center;text-decoration:none;border-radius:10px;margin:10px 0;font-weight:bold;font-size:15px;transition:0.3s}
        .btn-primary{background:linear-gradient(135deg,#007bff 0%,#0056b3 100%);color:white}
        .btn-success{background:linear-gradient(135deg,#28a745 0%,#20c997 100%);color:white}
        .btn-secondary{background:#6c757d;color:white}
        .btn:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(0,0,0,0.2)}
        .success{background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:12px;border-radius:8px;margin-bottom:15px}
        .error{background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:12px;border-radius:8px;margin-bottom:15px}
        .info{background:#d1ecf1;border:1px solid #bee5eb;color:#0c5460;padding:15px;border-radius:8px;margin-bottom:15px;font-size:14px}
    </style>
</head>
<body>
    <div class="navbar">
        <h1>TopupKing 👑</h1>
        <div class="user">
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:rgba(255,255,255,0.2);border:none;color:white;padding:8px 15px;border-radius:5px;cursor:pointer;font-size:14px">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <div class="card">
            <div class="balance">
                <h2>Wallet Balance</h2>
                <div class="amount"><span class="currency">₦</span>{{ number_format($wallet->balance, 2) }}</div>
            </div>
        </div>

        <div class="card">
            <h3>Quick Actions</h3>
            <a href="{{ route('wallet.fund') }}" class="btn btn-primary">💰 Fund Wallet</a>
            <a href="{{ route('wallet.buy-data') }}" class="btn btn-success">📱 Buy Data</a>
            <a href="#" class="btn btn-secondary" onclick="alert('Coming Soon!');return false;">📞 Buy Airtime</a>
            <a href="#" class="btn btn-secondary" onclick="alert('Coming Soon!');return false;">📺 Cable TV</a>
        </div>

        <div class="card">
            <h3>Recent Transactions</h3>
            @if($transactions->count() > 0)
                @foreach($transactions as $transaction)
                    <div style="padding:12px 0;border-bottom:1px solid #eee;display:flex;justify-content:space-between">
                        <div>
                            <strong>{{ ucfirst($transaction->type) }}</strong>
                            <div style="font-size:12px;color:#666">{{ $transaction->description }}</div>
                            <div style="font-size:11px;color:#999">{{ $transaction->created_at->diffForHumans() }}</div>
                        </div>
                        <div style="text-align:right">
                            <div style="font-weight:bold;color:{{ $transaction->type == 'credit' ? '#28a745' : '#dc3545' }}">
                                {{ $transaction->type == 'credit' ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                            </div>
                            <div style="font-size:11px;color:#666">{{ ucfirst($transaction->status) }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="info">No transactions yet. Fund your wallet to get started!</div>
            @endif
        </div>
    </div>
</body>
</html>
