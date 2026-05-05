<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - TopupKing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .header { background: #2563eb; color: white; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; }
        .card { background: white; padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .balance { font-size: 32px; font-weight: bold; color: #16a34a; margin: 10px 0; }
        .btn { background: #2563eb; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; margin: 5px; text-decoration: none; display: inline-block; }
        .btn-red { background: #dc2626; }
        .btn-green { background: #16a34a; }
    </style>
</head>
<body>
    <div class="header">
        <h2>TopupKing Dashboard</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-red">Logout</button>
        </form>
    </div>

    <div class="card">
        <h3>Welcome, {{ Auth::user()->name }}! ✅</h3>
        <p>Email: {{ Auth::user()->email }}</p>
        
        <h4>Wallet Balance</h4>
        <div class="balance">₦{{ number_format(Auth::user()->balance, 2) }}</div>
        
        <a href="/fund-wallet" class="btn btn-green">Fund Wallet</a>
        <a href="/airtime" class="btn">Buy Airtime</a>
        <a href="/data" class="btn">Buy Data</a>
    </div>

    <div class="card">
        <h4>Congrats Boss! Your authentication don work!</h4>
        <p>Next: Fund your wallet to start transactions</p>
    </div>
</body>
</html>
