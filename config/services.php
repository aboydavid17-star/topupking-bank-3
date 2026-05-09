<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'paystack' => [
        'publicKey' => env('PAYSTACK_PUBLIC_KEY'),
        'secretKey' => env('PAYSTACK_SECRET_KEY'),
        'baseUrl' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
        'merchantEmail' => 'davidjustice788@gmail.com',
    ],

    'vtpass' => [
        'username' => env('VTPASS_USERNAME'),
        'password' => env('VTPASS_PASSWORD'),
        'api_key' => env('VTPASS_API_KEY'),
        'secret_key' => env('VTPASS_SECRET_KEY'),
        'url' => env('VTPASS_URL', 'https://sandbox.vtpass.com/api'),
    ],

];
