<?php

return [
    'redis_api_cache' => [
        'ip' => env('REDIS_API_CACHE_IP', 'admin-test.docshero.de'),
        'port' => env('REDIS_API_CACHE_PORT', '6381'),
        'pw' => env('REDIS_API_CACHE_PW', '')
    ],

    'auth_service' => [
        'client_id' => env('AUTH_SERVICE_CLIENT_ID', 'urDfRe2u/yZ48wyWo0ugnFL0Ggo+ZZu1c1DbOWMVEG2NMGZaDGn/ZRd3qs8rT4zYvwM7YiROkadzUgAtyb/efA=='),
        'secret' => env('AUTH_SERVICE_SECRET', 'sQkPWDygs3LeriQ2f+CTYbDskCxk8/owTWdKUl2GqLUULWKD9/R8G9IyTHtKWV8l9Pdbgn7LG8TmtBxZdJZKbA=='),
        'base_url' => env('VITE_AUTH_SERVICE_URL', 'https://h2972847.stratoserver.net/o/public/index.php')
    ],
];
