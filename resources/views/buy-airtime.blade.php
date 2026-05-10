<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Airtime - TopupKing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card col-md-6 mx-auto">
            <div class="card-header bg-primary text-white">
                <h4>Buy Airtime</h4>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <form action="{{ route('airtime.buy') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Network</label>
                        <select name="network" class="form-control" required>
                            <option value="">Select Network</option>
                            <option value="mtn">MTN</option>
                            <option value="glo">GLO</option>
                            <option value="airtel">Airtel</option>
                            <option value="etisalat">9mobile</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" placeholder="08012345678" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (₦)</label>
                        <input type="number" name="amount" class="form-control" min="50" max="10000" placeholder="100" required>
                        <small class="text-muted">Min: ₦50 | Max: ₦10,000</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Buy Airtime Now</button>
                    <a href="/dashboard" class="btn btn-secondary w-100 mt-2">Back to Dashboard</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
