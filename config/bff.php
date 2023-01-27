<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Validation\Rules\File;
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
            // validate email address format and domain.
            'email' => ['bail', 'string', 'email:rfc,dns,spoof', 'indisposable'],

            // validate phone number country and type.
            'phone' => ['bail', 'string', 'phone:EG,SA,mobile'],

            // validate password strength and checks whether it appears on the have I been pwned list.
            'password' => ['bail', 'string', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],

            // please don't change this rule.
            // it's used to scan all uploaded files with Cisco ClamAV.
            '_custom_file_rule' => [
                'bail',
                File::types([
                    'jpg', 'png', 'gif', 'webp',
                    'pdf', 'csv',
                    'docx', 'xlsx', 'pptx',
                    'doc',  'xls', 'ppt',
                    'odt', 'ods', 'odp',
                ])
                    ->max(10 * 1024),
                'clamav',
            ],

            'image'     => [
                'bail',
                File::types(['jpg', 'png', 'gif', 'webp'])
                    ->image()
                    ->max(10 * 1024),
                'clamav',
            ],
        ],
    ],
];
