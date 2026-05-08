<!DOCTYPE html>
<html>
<head>
    <title>Fund Wallet - TopupKing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; background: #f0f2f5; margin: 0; padding: 20px; }
       .container { max-width: 500px; margin: 0 auto; }
       .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 12px; margin: 8px 0 16px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 16px; }
        label { font-weight: bold; color: #333; }
       .btn { width: 100%; padding: 15px; background: #4CAF50; color: white; border: none; border-radius: 8px; font-size: 16px; margin-top: 10px; cursor: pointer; }
       .back { color: #667eea; text-decoration: none; display: inline-block; margin-bottom: 15px; }
       .success { color: green; background: #e8f5e9; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
       .quick-amt { display: flex; gap: 10px; margin-bottom: 15px; }
       .quick-amt button { flex: 1; padding: 10px; background: #f0f2f5; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('wallet') }}" class="back">← Back to Wallet</a>
        <div class="card">
            <h2>💰 Fund Wallet</h2>
            @if(session('success'))<div class="success">{{ session('success') }}</div>@endif

            <form method="POST" action="{{ route('fund.wallet') }}">
                @csrf
                <label>Enter Amount (₦)</label>
                <input type="number" name="amount" id="amount" placeholder="500" required min="100">

                <label>Quick Select</label>
                <div class="quick-amt">
                    <button type="button" onclick="setAmount(500)">₦500</button>
                    <button type="button" onclick="setAmount(1000)">₦1,000</button>
                    <button type="button" onclick="setAmount(2000)">₦2,000</button>
                </div>

                <button type="submit" class="btn">Add Money to Wallet</button>
                <p style="font-size: 12px; color: #666; text-align: center; margin-top: 15px;">Test Mode: Money adds instantly without payment</p>
            </form>
        </div>
    </div>

    <script>
        function setAmount(val) {
            document.getElementById('amount').value = val;
        }
    </script>
</body>
</html>
