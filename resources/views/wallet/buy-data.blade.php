@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background: #5E3AEC; color: white; font-weight: 600;">
                    <a href="{{ route('wallet') }}" style="color: white; text-decoration: none;">← Back</a>
                    <span style="float: right;">Buy Data</span>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('wallet.buy-data.post') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="network" style="font-weight: 600; margin-bottom: 5px;">Network Provider</label>
                            <select name="network" id="network" class="form-control" required style="padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
                                <option value="">Select Network</option>
                                <option value="MTN">MTN</option>
                                <option value="GLO">GLO</option>
                                <option value="AIRTEL">AIRTEL</option>
                                <option value="9MOBILE">9MOBILE</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone" style="font-weight: 600; margin-bottom: 5px;">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="08012345678" maxlength="11" required style="padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
                        </div>

                        <div class="form-group mb-4">
                            <label for="plan" style="font-weight: 600; margin-bottom: 5px;">Select Plan</label>
                            <select name="plan" id="plan" class="form-control" required style="padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
                                <option value="">Choose Plan</option>
                                <option value="1GB - 30 Days - ₦300">1GB - 30 Days - ₦300</option>
                                <option value="2GB - 30 Days - ₦600">2GB - 30 Days - ₦600</option>
                                <option value="5GB - 30 Days - ₦1,500">5GB - 30 Days - ₦1,500</option>
                                <option value="10GB - 30 Days - ₦3,000">10GB - 30 Days - ₦3,000</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" style="background: #5E3AEC; border: none; padding: 14px; border-radius: 8px; font-weight: 600; font-size: 16px;">
                            Buy Data Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus {
    border-color: #5E3AEC;
    box-shadow: 0 0 0 0.2rem rgba(94, 58, 236, 0.25);
}
.alert {
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 20px;
}
.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>
@endsection
