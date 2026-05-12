<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fund Wallet - TopupKing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card col-md-6 mx-auto">
            <div class="card-header bg-success text-white">
                <h4>Fund Your Wallet</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('fund.initialize') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Amount (₦)</label>
                        <input type="number" name="amount" class="form-control" min="100" placeholder="Enter amount" required>
                        <small class="text-muted">Minimum: ₦100</small>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Pay with Paystack</button>
                    <a href="/dashboard" class="btn btn-secondary w-100 mt-2">Back to Dashboard</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
