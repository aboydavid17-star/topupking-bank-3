<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - TopupKing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f3f4f6; }
        .header { background: #2563eb; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .balance { font-size: 32px; font-weight: bold; color: #16a34a; margin: 15px 0; }
        .btn { background: #2563eb; color: white; padding: 12px 20px; text-decoration: none; border-radius: 6px; display: inline-block; margin: 5px 0; width: 100%; text-align: center; border: none; cursor: pointer; }
        .btn-red { background: #dc2626; }
        .btn-green { background: #16a34a; }
        .btn-secondary { background: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h2>TopupKing Dashboard</h2>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-red" style="width: auto; float: right;">Logout</button>
        </form>
    </div>

    <div class="card">
        <h3>Welcome, {{ Auth::user()->name }}</h3>
        <p>Email: {{ Auth::user()->email }}</p>
        
        <h4>Wallet Balance</h4>
        <div class="balance">₦{{ number_format(Auth::user()->wallet->balance ?? 0, 2) }}</div>
        
        <a href="{{ route('wallet.index') }}" class="btn btn-green">Fund Wallet</a>
        <a href="/airtime" class="btn btn-secondary">Buy Airtime</a>
        <a href="/data" class="btn btn-secondary">Buy Data</a>
    </div>
</body>
</html>
