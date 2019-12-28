<?php

return [
    'name' => 'RocketChat',
    'api' => [
        'enabled' => env('ROCKET_CHAT_API_ENABLED', false),
        'url' => env('ROCKET_CHAT_API_URL', 'http://localhost:3000'),
        'username' => env('ROCKET_CHAT_API_USERNAME', 'admin'),
        'password' => env('ROCKET_CHAT_API_PASSWORD', 'password'),
    ],
];