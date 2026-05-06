<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fund Wallet - TopupKing</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        .card { border: 1px solid #ddd; padding: 30px; border-radius: 8px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 12px; background: #0AA83F; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #08832f; }
        .balance { background: #f5f5f5; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .error { color: red; margin: 10px 0; }
        .success { color: green; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Fund Your Wallet 👑</h2>
        
        <div class="balance">
            <strong>Current Balance:</strong> ₦{{ number_format(Auth::user()->wallet, 2) }}
        </div>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('fund.wallet.post') }}">
            @csrf
            
            <label>Amount (₦)</label>
            <input type="number" name="amount" min="100" max="1000000" placeholder="Enter amount" required>
            @error('amount') <div class="error">{{ $message }}</div> @enderror
            
            <button type="submit">Pay with Paystack</button>
        </form>

        <p style="margin-top: 20px; text-align: center;">
            <a href="{{ route('dashboard') }}">Back to Dashboard</a>
        </p>
    </div>
</body>
</html>
