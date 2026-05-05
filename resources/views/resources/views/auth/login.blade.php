<!DOCTYPE html>
<html>
<head>
    <title>Login - TopupKing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 400px; margin: 50px auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 16px; }
        .btn { background: #2563eb; color: white; padding: 12px; border: none; border-radius: 6px; width: 100%; font-size: 16px; cursor: pointer; }
        .error { color: #dc2626; font-size: 14px; margin-bottom: 10px; }
        a { color: #2563eb; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Login to TopupKing</h2>
        @if($errors->any()) <div class="error">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn" type="submit">Login</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">No account? <a href="{{ route('register') }}">Register</a></p>
    </div>
</body>
</html>
