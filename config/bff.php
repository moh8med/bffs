<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Validation\Rules\Password;

return [
    'base_url' => env('BFF_BASE_URL'),

    'geo' => [
        'enabled' => env('BFF_GEOIP_ENABLED', true),

        'countries' => [
            'only'   => [], // EG, SA, etc...
            'except' => [   // RU, CN, etc...
                'RU', 'UA', 'PL',
                'CN', 'HK', 'TW', 'SG',
                'IL', 'LY', 'MA',
                'IR', 'PK', 'AF', 'ID',
            ],
        ],

        // See: https://www.php.net/manual/en/function.geoip-continent-code-by-name.php
        'continents' => [
            'only'   => [], // AF, AN, AS, EU, NA, OC, SA.
            'except' => [], // AF, AN, AS, EU, NA, OC, SA.
        ],
    ],

    'validation' => [
        'rules' => [
            'email' => ['bail', 'string', 'email:rfc,dns,spoof', 'indisposable'],
            'phone' => ['bail', 'string', 'phone:EG,SA,mobile'],
            'password' => ['bail', 'string', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
        ],
    ],
];
