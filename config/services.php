<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe'   => [
        'model'  => App\Models\CoreUsers::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID', '2220797271491128'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', '0c124cb2eca0c3c1f7bafb010da3fb0d'),
        'redirect'      => env('FACEBOOK_LOGIN_CALLBACK', "https://pizo.thietke24h.com/api/v1.0/user/loginfacebook"),
    ],
    'google'   => [
        'client_id'     => env('GOOGLE_CLIENT_ID', '653080496864-7oq6pc40bal419i9l09ug2lceck9iijt.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', 'FLtucTudWziHQ1Y0kE9U1bZa'),
        'redirect'      => env('GOOGLE_LOGIN_CALLBACK', "https://pizo.thietke24h.com/api/v1.0/user/logingoogle"),
    ],
];
