<?php

return [
    'name' => 'Core',
    'locale' => [
        'en',
        'cn',
        'ru',
        'ua'
    ],
    'default_language' => 'en',
    'translation_fallback_enabled' => env('TRANSLATION_FALLBACK_ENABLED', true),

    'access_levels' => ['admin', 'client', 'guest'],
    'use_redis' => env('USE_REDIS', false),

    'category' => [
        'industrial' => [
            'type' => 'company',
            'visible' => 1,
            'child' => [
                'Machinery & Engineering',
                'Food & Beverages',
                'ICT / High-tech / Innovations',
                'Education & Tourism',
                'Creative Industries',
                'State Enterprises',
                'Other',
            ],
        ],
        'investments' => [
            'type' => 'project',
            'visible' => 0,
            'child' => []
            ],
    ],
];
