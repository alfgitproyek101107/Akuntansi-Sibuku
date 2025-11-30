<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp API integration supporting multiple providers.
    | Currently supports: fonnte, ultramsg, cloud_api
    |
    */

    'provider' => env('WHATSAPP_PROVIDER', 'fonnte'),

    'providers' => [
        'fonnte' => [
            'api_key' => env('FONNTE_API_KEY'),
            'url' => env('FONNTE_URL', 'https://api.fonnte.com'),
        ],

        'ultramsg' => [
            'instance_id' => env('ULTRAMSG_INSTANCE_ID'),
            'token' => env('ULTRAMSG_TOKEN'),
            'url' => env('ULTRAMSG_URL', 'https://api.ultramsg.com'),
        ],

        'cloud_api' => [
            'access_token' => env('WHATSAPP_CLOUD_API_ACCESS_TOKEN'),
            'phone_number_id' => env('WHATSAPP_CLOUD_API_PHONE_NUMBER_ID'),
            'url' => env('WHATSAPP_CLOUD_API_URL', 'https://graph.facebook.com/v18.0'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Demo Mode Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for demo mode to prevent real WhatsApp sending
    |
    */

    'demo' => [
        'enabled' => env('WHATSAPP_DEMO_ENABLED', false),
        'test_number' => env('WHATSAPP_DEMO_TEST_NUMBER', '6281234567890'),
        'watermark_text' => 'DEMO MODE - Sistem Akuntansi Sibuku',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    */

    'timeout' => env('WHATSAPP_TIMEOUT', 30), // seconds
    'retry_attempts' => env('WHATSAPP_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('WHATSAPP_RETRY_DELAY', 30), // seconds

    /*
    |--------------------------------------------------------------------------
    | Report Settings
    |--------------------------------------------------------------------------
    */

    'reports' => [
        'max_text_length' => 4096, // WhatsApp text limit
        'pdf_quality' => env('WHATSAPP_PDF_QUALITY', 'high'),
        'timezone' => env('WHATSAPP_TIMEZONE', 'Asia/Jakarta'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'enabled' => env('WHATSAPP_LOGGING_ENABLED', true),
        'channel' => env('WHATSAPP_LOG_CHANNEL', 'whatsapp'),
        'level' => env('WHATSAPP_LOG_LEVEL', 'info'),
    ],
];