<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Data - TopupKing</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f3f4f6; padding: 16px; }
        .container { max-width: 500px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 16px; margin-bottom: 16px; }
        .header a { color: white; text-decoration: none; font-size: 14px; }
        .card { background: white; padding: 24px; border-radius: 16px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; }
        input, select { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; }
        .btn { width: 100%; padding: 14px; background: #667eea; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .error { color: #dc2626; font-size: 13px; margin-top: 6px; }
        .alert { padding: 14px; border-radius: 10px; margin-bottom: 16px; background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('wallet') }}">← Back to Wallet</a>
            <h2 style="margin-top: 8px;">📱 Buy Data</h2>
        </div>

        @error('balance')<div class="alert">{{ $message }}</div>@enderror
        @error('vtpass')<div class="alert">{{ $message }}</div>@enderror

        <div class="card">
            <form method="POST" action="{{ route('wallet.data.store') }}">
                @csrf
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="08012345678" required>
                    @error('phone')<div class="error">{{ $message }}</div>@enderror
                </div>
                
                <div class="form-group">
                    <label>Data Plan</label>
                    <select name="variation_code" id="plan" required onchange="updateAmount()">
                        <option value="">Select Plan</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan['variation_code'] }}" data-amount="{{ $plan['variation_amount'] }}">
                                {{ $plan['name'] }} - ₦{{ $plan['variation_amount'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('variation_code')<div class="error">{{ $message }}</div>@enderror
                </div>
                
                <input type="hidden" name="amount" id="amount">
                <button type="submit" class="btn">Buy Data</button>
            </form>
        </div>
    </div>
    
    <script>
        function updateAmount() {
            const select = document.getElementById('plan');
            const amount = select.options[select.selectedIndex].getAttribute('data-amount');
            document.getElementById('amount').value = amount;
        }
    </script>
</body>
</html>
