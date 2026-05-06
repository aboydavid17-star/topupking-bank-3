<!DOCTYPE html>
<html>
<head>
    <title>Fund Wallet - TopupKing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; padding: 20px; max-width: 400px; margin: 50px auto; }
        input { width: 100%; padding: 12px; margin: 10px 0; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; }
    </style>
</head>
<body>
    <h2>Fund Your Wallet 👑</h2>
    <p>Current Balance: ₦{{ Auth::user()->wallet ?? '0.00' }}</p>
    
    <form method="POST" action="{{ route('fund.wallet.post') }}">
        @csrf
        <label>Amount (₦)</label>
        <input type="number" name="amount" min="100" value="100" required>
        <button type="submit">Pay with Paystack</button>
    </form>
    
    <p><a href="/dashboard">Back to Dashboard</a></p>
</body>
</html>
