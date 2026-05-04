<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - TopupKing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 20px; }
        .header { background: #2563eb; color: white; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; }
        .card { background: white; padding: 30px; border-radius: 8px; margin-top: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        button { padding: 10px 15px; background: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="header">
        <h2>TopupKing Dashboard</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
    <div class="card">
        <h3>Welcome, {{ Auth::user()->name }}! ✅</h3>
        <p>Email: {{ Auth::user()->email }}</p>
        <p><strong>Congrats Boss! Your authentication don work!</strong></p>
    </div>
</body>
</html>
