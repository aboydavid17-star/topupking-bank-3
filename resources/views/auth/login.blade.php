<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TopupKing</title>
</head>
<body style="font-family:Arial;max-width:400px;margin:50px auto;padding:20px;background:#f5f5f5">
    <div style="background:white;padding:30px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1)">
        <h2 style="text-align:center;margin-bottom:30px;color:#0AA83F">Login to TopupKing</h2>
        
        @if($errors->any())
            <div style="color:#dc3545;background:#f8d7da;padding:12px;border-radius:4px;margin-bottom:20px">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" placeholder="Email Address" required 
                   style="width:100%;padding:12px;margin:10px 0;border:1px solid #ddd;border-radius:4px;box-sizing:border-box">
            <input type="password" name="password" placeholder="Password" required 
                   style="width:100%;padding:12px;margin:10px 0;border:1px solid #ddd;border-radius:4px;box-sizing:border-box">
            <button type="submit" 
                    style="width:100%;padding:14px;background:#0AA83F;color:white;border:none;border-radius:4px;cursor:pointer;font-size:16px;margin-top:10px">
                Login
            </button>
        </form>

        <p style="text-align:center;margin-top:20px;color:#666">
            No account? <a href="{{ route('register') }}" style="color:#0AA83F;text-decoration:none">Register here</a>
        </p>
    </div>
</body>
</html>
