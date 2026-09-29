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
        // Paysera WebToPay — projectid + project password from the Paysera
        // project settings page. test=1 uses fake payments (no real money).
        'paysera' => [
            'pay_url' => env('PAYSERA_PAY_URL', 'https://www.paysera.com/pay'),
            'project_id' => env('PAYSERA_PROJECT_ID'),
            'project_password' => env('PAYSERA_PROJECT_PASSWORD'),
            'test' => env('PAYSERA_TEST', false),
        ],
        'makecommerce' => [
            'api_url' => env('MAKECOMMERCE_API_URL', 'https://api.makecommerce.net'),
            'access_key' => env('PAYMENT_ACCESS_KEY'),
            'secret_key' => env('PAYMENT_SECRET_KEY'),
            'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET'),
        ],
        // Local development only — fake gateway, no external calls.
        'test' => [
            'api_url' => '',
            'access_key' => 'test',
            'secret_key' => env('PAYMENT_SECRET_KEY', 'test-webhook-secret'),
            'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET', 'test-webhook-secret'),
        ],
    ],

    'notification_email' => env('ORDER_NOTIFICATION_EMAIL', 'roxana71@protonmail.com'),

    'currency' => 'EUR',

    'return_urls' => [
        'success' => env('PAYMENT_RETURN_SUCCESS', env('APP_URL') . '/order/success'),
        'cancelled' => env('PAYMENT_RETURN_CANCELLED', env('APP_URL') . '/order/cancelled'),
    ],

];
