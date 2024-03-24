<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'redis' => [
        'logging_ip' => env('LOGGING_IP', 'admin-test.docshero.de'),
        'logging_port' => env('LOGGING_PORT', '6381'),
        'logging_service_name' => env('LOGGING_SERVICE_NAME', 'DocsHero')
    ],
    'users' => [
        'vite_auth_service_url' => env('VITE_AUTH_SERVICE_URL', 'https://h2972847.stratoserver.net/o/public/index.php'),
    ],
    'environment' => [
        'app_environment' => env('APP_ENV', 'local')
    ],
    'licensing' => [
        'vite_license_service_url' => env('VITE_LICENSE_SERVICE_URL'),
    ],
    'mail' => [
        'redis_ip' => env('REDIS_MAIL_IP'),
        'redis_port' => env('REDIS_MAIL_PORT'),
        'vite_mail_service_url' => env('VITE_MAIL_SERVICE_URL')
    ],
    'elo' => [
        'enqueue_url' => env('ENQUEUE_URL')
    ],
    'kafka' => [
        'broker_ip' => env('KAFKA_BROKER_IP', '5.9.158.69:9092'),
        'topic' => env('KAFKA_TOPIC', 'users')
    ]
];
