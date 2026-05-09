<!DOCTYPE html>
<html>
<head>
    <title>Pay with Paystack</title>
    <script src="https://js.paystack.co/v1/inline.js"></script>
</head>
<body style="background: #f3f4f6; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif;">
    <div style="text-align: center;">
        <h2>Redirecting to Paystack...</h2>
        <p>Amount: ₦{{ number_format($amount / 100, 2) }}</p>
    </div>

    <script>
        let handler = PaystackPop.setup({
            key: '{{ config("services.paystack.publicKey") }}',
            email: '{{ $email }}',
            amount: {{ $amount }},
            ref: '{{ $reference }}',
            callback: function(response) {
                window.location.href = "{{ route('pay.callback') }}?reference=" + response.reference;
            },
            onClose: function() {
                window.location.href = "{{ route('wallet.fund') }}";
            }
        });
        handler.openIframe();
    </script>
</body>
</html>
