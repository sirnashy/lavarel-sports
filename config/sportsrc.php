<?php

return [
    'base_url' => env('SPORTSRC_BASE_URL', 'https://api.sportsrc.org/v2/'),
    'api_key' => env('SPORTSRC_API_KEY', ''),
    'timeout' => env('SPORTSRC_TIMEOUT', 30),
    'retries' => env('SPORTSRC_RETRIES', 3),
    'retry_delay' => env('SPORTSRC_RETRY_DELAY', 1000), // ms

    'cache_ttl' => [
        'live' => 30,
        'scores' => 15,
        'incidents' => 20,
        'stats' => 30,
        'matches' => 120,
        'detail' => 30,
        'lineups' => 60,
        'h2h' => 3600,
        'standings' => 1800,
        'highlights' => 300,
        'last_matches' => 600,
        'sports' => 3600,
    ],

    'rate_limit' => [
        'per_minute' => env('SPORTSRC_RATE_LIMIT', 60),
    ],
];