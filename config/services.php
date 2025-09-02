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

    'livekit' => [
        'ws_url' => env('LIVEKIT_WS_URL', 'ws://localhost:7880'),
        'api_key' => env('LIVEKIT_API_KEY', 'devkey'),
        'api_secret' => env('LIVEKIT_API_SECRET', 'devsecret'),
        'stun_url' => env('STUN_URL', 'stun:stun.l.google.com:19302'),
        'turn_url' => env('TURN_URL'),
        'turn_tls_url' => env('TURN_TLS_URL'),
        'turn_username' => env('TURN_USERNAME'),
        'turn_password' => env('TURN_PASSWORD'),
        'token_ttl_minutes' => env('LIVEKIT_TOKEN_TTL', 60),
    ],

];
