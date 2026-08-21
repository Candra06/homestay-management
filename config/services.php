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
    'url_mengantar' => env('BASE_URL_MENGANTAR'),
    'key_mengantar' => env('API_KEY_MENGANTAR'),
    'address_id' => env('ADDRESS_ID'),
    'origin_id' => env('ORIGIN_ID'),
    'courir' => env('COURIR'),
    'app_key' => env('APP_KEY_GATEWAY'),
    'url_whatsapp' => env('BASE_URL_WHATSAPP'),
    'user_whatsapp_id' => env('USER_WHATSAPP_ID'),
    'device_whatsapp_id' => env('DEVICE_WHATSAPP_ID'),
    'auth_key' => env('AUTH_KEY_GATEWAY'),
];
