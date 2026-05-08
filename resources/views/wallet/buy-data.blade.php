<!DOCTYPE html>
<html>
<head>
    <title>Buy Data - TopupKing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; background: #f0f2f5; margin: 0; padding: 20px; }
       .container { max-width: 500px; margin: 0 auto; }
       .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 12px; margin: 8px 0 16px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 16px; }
        label { font-weight: bold; color: #333; }
       .btn { width: 100%; padding: 15px; background: #2196F3; color: white; border: none; border-radius: 8px; font-size: 16px; margin-top: 10px; cursor: pointer; }
       .back { color: #667eea; text-decoration: none; display: inline-block; margin-bottom: 15px; }
       .success { color: green; background: #e8f5e9; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
       .error { color: red; background: #ffebee; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('wallet') }}" class="back">← Back to Wallet</a>
        <div class="card">
            <h2>📱 Buy Data Bundle</h2>
            @if(session('success'))<div class="success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="error">{{ session('error') }}</div>@endif

            <form method="POST" action="{{ route('buy.data') }}">
                @csrf
                <label>Network</label>
                <select name="network" required>
                    <option value="">Select Network</option>
                    <option value="MTN">MTN</option>
                    <option value="GLO">GLO</option>
                    <option value="AIRTEL">Airtel</option>
                    <option value="9MOBILE">9Mobile</option>
                </select>

                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="08012345678" required maxlength="11" pattern="[0-9]{11}">

                <label>Data Plan</label>
                <select name="plan" required>
                    <option value="">Select Plan</option>
                    <option value="1GB - 30 Days - ₦300">1GB - 30 Days - ₦300</option>
                    <option value="2GB - 30 Days - ₦600">2GB - 30 Days - ₦600</option>
                    <option value="3GB - 30 Days - ₦1,000">3GB - 30 Days - ₦1,000</option>
                    <option value="5GB - 30 Days - ₦1,500">5GB - 30 Days - ₦1,500</option>
                    <option value="10GB - 30 Days - ₦3,000">10GB - 30 Days - ₦3,000</option>
                </select>

                <button type="submit" class="btn">Buy Data Now</button>
            </form>
        </div>
    </div>
</body>
</html>
