<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet - TopupKing</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }
        .container { max-width: 600px; margin: 40px auto; }
        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #333; margin-bottom: 10px; font-size: 28px; }
        .welcome { color: #666; margin-bottom: 30px; }
        .balance-box {
            background: linear-gradient(135deg, #0A9A4A 0%, #08832f 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .balance-label { font-size: 14px; opacity: 0.9; margin-bottom: 5px; }
        .balance-amount { font-size: 36px; font-weight: bold; }
        .btn {
            display: inline-block;
            padding: 14px 30px;
            background: #0A9A4A;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .btn:hover { background: #08832f; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>My Wallet 💰</h1>
            <p class="welcome">Welcome back, {{ $user->name }}</p>

            <div class="balance-box">
                <div class="balance-label">Available Balance</div>
                <div class="balance-amount">₦{{ number_format($balance, 2) }}</div>
            </div>

            <a href="#" class="btn">Fund Wallet</a>
        </div>
    </div>
</body>
</html>
