<?php

return [

    'default' => env('PAYMENT_PROVIDER', 'montonio'),

    'providers' => [
        'montonio' => [
            'api_url' => env('MONTONIO_API_URL', 'https://api.montonio.com'),
            'access_key' => env('PAYMENT_ACCESS_KEY'),
            'secret_key' => env('PAYMENT_SECRET_KEY'),
            'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET'),
        ],
        'paysera' => [
            'api_url' => env('PAYSERA_API_URL', 'https://checkout.paysera.com'),
            'access_key' => env('PAYMENT_ACCESS_KEY'),
            'secret_key' => env('PAYMENT_SECRET_KEY'),
            'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET'),
        ],
        'makecommerce' => [
            'api_url' => env('MAKECOMMERCE_API_URL', 'https://api.makecommerce.net'),
            'access_key' => env('PAYMENT_ACCESS_KEY'),
            'secret_key' => env('PAYMENT_SECRET_KEY'),
            'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET'),
        ],
    ],

    'notification_email' => env('ORDER_NOTIFICATION_EMAIL', 'roxana71@protonmail.com'),

    'currency' => 'EUR',

    'return_urls' => [
        'success' => env('PAYMENT_RETURN_SUCCESS', env('APP_URL') . '/order/success'),
        'cancelled' => env('PAYMENT_RETURN_CANCELLED', env('APP_URL') . '/order/cancelled'),
    ],

];
