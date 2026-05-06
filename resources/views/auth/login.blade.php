<!DOCTYPE html>
<html>
<head>
    <title>Login - TopupKing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; padding: 20px; max-width: 400px; margin: 50px auto; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 12px; background: #0d6efd; color: white; border: none; }
    </style>
</head>
<body>
    <h2>Login to TopupKing 👑</h2>
    
    @if($errors->any())
        <div style="color: red;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label>Email</label>
        <input type="email" name="email" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>
    
    <p style="text-align:center; margin-top:20px;">
        No account? <a href="{{ route('register') }}">Register</a>
    </p>
</body>
</html>
