@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Buy Data Bundle</div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('data.buy') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Network</label>
                            <select name="network" id="network" class="form-control" required>
                                <option value="">Select Network</option>
                                <option value="1">MTN</option>
                                <option value="2">GLO</option>
                                <option value="3">AIRTEL</option>
                                <option value="4">9MOBILE</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="08012345678" required>
                        </div>

                        <div class="mb-3">
                            <label>Data Plan</label>
                            <select name="dataplan" id="dataplan" class="form-control" required>
                                <option value="">Select Network First</option>
                            </select>
                            <small>Wallet Balance: ₦{{ Auth::user()->wallet_balance }}</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Buy Data Now</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
// CHANGE THESE PRICES TO SET YOUR OWN PROFIT
document.getElementById('network').addEventListener('change', function() {
    let network = this.value;
    let planSelect = document.getElementById('dataplan');
    planSelect.innerHTML = '<option value="">Loading plans...</option>';

    if(network == '') return;

    let plans = {
        '1': [ // MTN - Clubkonnect charges ~₦270 for 1GB. Sell at ₦300 = ₦30 profit
            {'code': '500', 'name': '500MB - 30 days', 'price': 150},
            {'code': '1', 'name': '1GB - 30 days', 'price': 300},
            {'code': '2', 'name': '2GB - 30 days', 'price': 600},
            {'code': '3', 'name': '3GB - 30 days', 'price': 900},
            {'code': '5', 'name': '5GB - 30 days', 'price': 1500},
        ],
        '3': [ // AIRTEL
            {'code': '750', 'name': '750MB - 14 days', 'price': 500},
            {'code': '1.5', 'name': '1.5GB - 30 days', 'price': 1000},
            {'code': '2', 'name': '2GB - 30 days', 'price': 1200},
            {'code': '6', 'name': '6GB - 30 days', 'price': 2500},
        ],
        '2': [ // GLO
            {'code': '1.05', 'name': '1.05GB - 14 days', 'price': 500},
            {'code': '2.9', 'name': '2.9GB - 30 days', 'price': 1000},
            {'code': '4.1', 'name': '4.1GB - 30 days', 'price': 1500},
        ],
        '4': [ // 9MOBILE
            {'code': '1', 'name': '1GB - 30 days', 'price': 1000},
            {'code': '1.5', 'name': '1.5GB - 30 days', 'price': 1200},
            {'code': '2', 'name': '2GB - 30 days', 'price': 2000},
        ]
    };

    planSelect.innerHTML = '<option value="">Select Data Plan</option>';

    if(plans[network]) {
        plans[network].forEach(function(plan) {
            planSelect.innerHTML += `<option value="${plan.code}|${plan.price}">${plan.name} - ₦${plan.price}</option>`;
        });
    }
});
</script>
@endsection
