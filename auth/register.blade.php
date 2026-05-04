<!DOCTYPE html>
<html>
<head><title>Register - TopupKing</title></head>
<body style="font-family: Arial; display: flex; justify-content: center; margin-top: 50px;">
    <div style="width: 300px; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
        <h2>Register for TopupKing</h2>
        <form method="POST" action="/register">
            @csrf
            <input type="text" name="name" placeholder="Name" required style="width: 100%; padding: 8px; margin: 5px 0;"><br>
            <input type="email" name="email" placeholder="Email" required style="width: 100%; padding: 8px; margin: 5px 0;"><br>
            <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 8px; margin: 5px 0;"><br>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required style="width: 100%; padding: 8px; margin: 5px 0;"><br>
            <button type="submit" style="width: 100%; padding: 10px; background: blue; color: white; border: none;">Register</button>
        </form>
        <p>Have account? <a href="/login">Login</a></p>
    </div>
</body>
</html>
