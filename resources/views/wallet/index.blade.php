<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TopupKing - Wallet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f3f4f6; 
            padding: 16px;
            color: #1f2937;
        }
        .container { max-width: 500px; margin: 0 auto; }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 24px; 
            border-radius: 16px; 
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .header h1 { font-size: 24px; margin-bottom: 4px; }
        .header p { opacity: 0.95; font-size: 14px; }
        .card { 
            background: white; 
            padding: 20px; 
            border-radius: 16px; 
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .balance-card { text-align: center; }
        .balance-label { color: #6b7280; font-size: 13px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .balance-amount { font-size: 42px; font-weight: 700; color: #111827; }
        .btn { 
            display: block; 
            width: 100%; 
            padding: 14px; 
            margin: 8px 0; 
            border: none; 
            border-radius: 10px; 
            font-size: 15px; 
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
        }
        .btn:active { transform: scale(0.97); }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #5568d3; }
        .btn-secondary { background: #f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; }
        .alert { 
            padding: 14px; 
            border-radius: 10px; 
            margin-bottom: 16px;
            background: #d1fae5; 
            color: #065f46;
            border-left: 4px solid #10b981;
            font-size: 14px;
        }
        .section-title { 
            font-size: 16px; 
            font-weight: 600; 
            margin-bottom: 12px; 
            color: #111827;
        }
        .logout { text-align: center; margin-top: 24px; }
        .logout button { 
            background: none; 
            border: none; 
            color: #667eea; 
            cursor: pointer; 
            font-size: 14px;
            text-decoration: underline;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>TopupKing 👑</h1>
            <p>{{ $user->name }}</p>
        </div>

        @if(session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card balance-card">
            <div class="balance-label">Wallet Balance</div>
            <div class="balance-amount">₦{{ number_format($balance, 2) }}</div>
        </div>

        <div class="card">
            <div class="section-title">Quick Actions</div>
            <a href="{{ route('wallet.fund') }}" class="btn btn-primary">💰 Fund Wallet</a>
            <a href="{{ route('wallet.data') }}" class="btn btn-secondary">📱 Buy Data</a>
            <a href="{{ route('wallet.airtime') }}" class="btn btn-secondary">📞 Buy Airtime</a>
        </div>

        <div class="logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
