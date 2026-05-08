<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TopupKing</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:Arial,sans-serif;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;display:flex;justify-content:center;align-items:center;padding:20px}
        .box{background:white;padding:40px;border-radius:15px;box-shadow:0 10px 40px rgba(0,0,0,0.2);width:100%;max-width:420px}
        h2{text-align:center;color:#333;margin-bottom:30px;font-size:28px}
        input{width:100%;padding:14px;margin:10px 0;border:2px solid #e0e0e0;border-radius:8px;font-size:15px;transition:0.3s}
        input:focus{border-color:#667eea;outline:none}
        button{width:100%;padding:14px;background:linear-gradient(135deg,#28a745 0%,#20c997 100%);color:white;border:none;border-radius:8px;font-size:16px;font-weight:bold;cursor:pointer;margin-top:10px;transition:0.3s}
        button:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(40,167,69,0.4)}
        .error{background:#fee;border:1px solid #fcc;color:#c33;padding:12px;border-radius:8px;margin-bottom:15px;font-size:14px}
        p{text-align:center;margin-top:20px;color:#666}
        a{color:#667eea;text-decoration:none;font-weight:bold}
        a:hover{text-decoration:underline}
    </style>
</head>
<body>
    <div class="box">
        <h2>Join TopupKing 👑</h2>
        
        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required autofocus>
            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
            <input type="password" name="password" placeholder="Password (min 8 chars)" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <button type="submit">Create Account</button>
        </form>
        
        <p>Already have an account? <a href="{{ route('login') }}">Login Here</a></p>
    </div>
</body>
</html>
