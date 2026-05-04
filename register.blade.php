<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Topupking Bank</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h1 { text-align: center; color: #1f2937; margin-bottom: 24px; }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #dc2626; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; margin-top: 12px; }
        button:hover { background: #b91c1c; }
        .error { color: #dc2626; font-size: 14px; margin-top: 4px; }
        .link { text-align: center; margin-top: 16px; }
        a { color: #dc2626; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Create Account</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror
            
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror
            
            <input type="password" name="password" placeholder="Password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror
            
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            
            <button type="submit">Register</button>
        </form>
        <div class="link">
            Already have account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>
</body>
</html>
