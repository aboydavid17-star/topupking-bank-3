<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fund Wallet - TopupKing</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f3f4f6; 
            padding: 16px;
            color: #1f2937;
        }
        .container { max-width: 500px; margin: 0 auto; }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 20px; 
            border-radius: 16px; 
            margin-bottom: 16px;
        }
        .header a { color: white; text-decoration: none; font-size: 14px; }
        .card { 
            background: white; 
            padding: 24px; 
            border-radius: 16px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .form-group { margin-bottom: 20px; }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600; 
            font-size: 14px;
            color: #374151;
        }
        input { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #e5e7eb; 
            border-radius: 10px; 
            font-size: 16px;
            transition: border 0.2s;
        }
        input:focus { 
            outline: none; 
            border-color: #667eea; 
        }
        .btn { 
            width: 100%; 
            padding: 14px; 
            background: #667eea; 
            color: white; 
            border: none; 
            border-radius: 10px; 
            font-size: 16px; 
            font-weight: 600;
            cursor: pointer;
        }
        .btn:active { transform: scale(0.98); }
        .alert { 
            padding: 14px; 
            border-radius: 10px; 
            margin-bottom: 16px;
            background: #d1fae5; 
            color: #065f46;
        }
        .error { 
            color: #dc2626; 
            font-size: 13px; 
            margin-top: 6px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('wallet') }}">← Back to Wallet</a>
            <h2 style="margin-top: 8px;">💰 Fund Wallet</h2>
        </div>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <div class="card">
            <form method="POST" action="{{ route('wallet.fund.store') }}">
                @csrf
                <div class="form-group">
                    <label>Enter Amount (₦)</label>
                    <input type="number" name="amount" placeholder="100" min="100" max="50000" required>
                    @error('amount')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn">Continue to Paystack</button>
            </form>
        </div>
    </div>
</body>
</html>
