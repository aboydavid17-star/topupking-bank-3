<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopupKing Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .dashboard-card { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card dashboard-card">
            <div class="card-header bg-primary text-white">
                <h3>TopupKing Dashboard</h3>
                <form action="{{ route('logout') }}" method="POST" class="float-end">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
            <div class="card-body">
                <h5 class="card-title">Welcome, {{ Auth::user()->name }}</h5>
                <p class="card-text">Email: {{ Auth::user()->email }}</p>
                
                <hr>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <h6 class="fw-bold">Wallet Balance</h6>
                <h1 class="text-success mb-4">₦{{ number_format($balance ?? 0, 2) }}</h1>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('fund.wallet') }}" class="btn btn-success btn-lg">Fund Wallet</a>
                    <a href="{{ route('airtime') }}" class="btn btn-secondary btn-lg">Buy Airtime</a>
                    <a href="{{ route('data') }}" class="btn btn-secondary btn-lg">Buy Data</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
