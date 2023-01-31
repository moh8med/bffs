<?php

use Illuminate\Support\Facades\Facade;

return [
    'base_url' => env('BFF_BASE_URL'),

    'geo' => [
        'enabled' => env('BFF_GEOIP_ENABLED', true),

        'countries' => [
            'only'   => [], // EG, SA, etc...
            'except' => [], // RU, CN, etc...
        ],

        // See: https://www.php.net/manual/en/function.geoip-continent-code-by-name.php
        'continents' => [
            'only'   => [], // AF, AN, AS, EU, NA, OC, SA.
            'except' => [], // AF, AN, AS, EU, NA, OC, SA.
        ],
    ],
];
