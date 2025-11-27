<?php

return [
    'default_length' => 6,
    'default_expiry' => 5,

    'dev_mode' => [
        'enabled' => env('OTP_DEV_MODE', false),
        'fallback' => env('OTP_DEV_FALLBACK', '123456'),
    ],

    'types' => [
        'phone' => [
            'length' => 6,
            'expires_after_minutes' => 5,
            'dev_number' => env('OTP_DEV_PHONE', '123456'),
        ],
        'email' => [
            'length' => 6,
            'expires_after_minutes' => 10,
            'dev_number' => env('OTP_DEV_EMAIL', '123456'),
        ],
    ],
];

