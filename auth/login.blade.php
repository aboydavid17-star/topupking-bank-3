<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #111827; }
        input { width: 100%; padding: 12px; margin: 8px 0 16px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; background: #3b82f6; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #2563eb; }
        .error { color: #ef4444; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login to TopupKing</h2>
        
        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
