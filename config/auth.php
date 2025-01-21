<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'shops',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'shops',
        ],
    ],

    'providers' => [
        'shops' => [
            'driver' => 'eloquent',
            'model' => App\Models\Shop::class,
        ],
    ],

    'passwords' => [
        'shops' => [
            'provider' => 'shops',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
