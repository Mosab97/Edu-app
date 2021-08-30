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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID', '77lywgc9nmj4re'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET', 'CmC4y4RT4ZQgGWjk'),
        'redirect' => env('LINKEDIN_CALLBACK_URL', 'http://ingaz.test/linkedin/callback'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID','267887077730-3nhr70cu93db86t77o18iru54id632uh.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET','hw-IiCal66U7ZJ-ODuCUWJIE'),
        'redirect' => env('GOOGLE_CALLBACK_URL', 'http://127.0.0.1:8000/login/google/callback'),
    ],

];
