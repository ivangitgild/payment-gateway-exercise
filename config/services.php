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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'paymongo' => [
        'mode' => env('PAYMONGO_MODE', 'test'),
        'url' => 'https://api.paymongo.com/v1',
        'publick_api_key' => env('PAYMONGO_PUBLIC_API_KEY'),
        'secret_api_key' => env('PAYMONGO_SECRET_API_KEY'),
        'webhook_secret_key' => env('PAYMONGO_WEBHOOK_SECRET_KEY')
    ],
    'payment_redirects' => [
        'success' => env('PAYMENT_SUCCESS_URL'),
        'failed' => env('PAYMENT_FAILED_URL'),
        'cancelled' => env('PAYMENT_CANCELLED_URL')
    ],
];
