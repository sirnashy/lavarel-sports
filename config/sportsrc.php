<?php

return [
    'base_url' => env('SPORTSRC_BASE_URL', 'https://api.sportsrc.org/v2/'),
    'api_key' => env('SPORTSRC_API_KEY', ''),
    'timeout' => env('SPORTSRC_TIMEOUT', 30),
    'retries' => env('SPORTSRC_RETRIES', 3),
    'retry_delay' => env('SPORTSRC_RETRY_DELAY', 1000), // ms

    'cache_ttl' => [
        'account' => 300,
        'live' => 30,
        'sports' => 86400,
        'matches' => 300,
        'detail' => 120,
        'scores' => 300,
        'lineups' => 3600,
        'stats' => 1800,
        'incidents' => 1200,
        'h2h' => 3600,
        'standings' => 1800,
        'highlights' => 300,
        'last_matches' => 600,
        'graph' => 300,
    ],

    'rate_limit' => [
        'per_minute' => env('SPORTSRC_RATE_LIMIT', 60),
    ],
];