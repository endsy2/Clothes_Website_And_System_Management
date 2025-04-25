<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'customers'),  // Change this to 'customers' as there is no 'users' broker
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Define every authentication guard for your application.
    |
    */

    'guards' => [
        'customer' => [
            'driver' => 'session',
            'provider' => 'customers',  // Points to the customers provider
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',  // Points to the admins provider
        ],
        'web' => [
            'driver' => 'session',
            'provider' => 'customers',  // Set default provider to 'customers' (or whatever fits your needs)
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Define how the users are retrieved from the database.
    |
    */

    'providers' => [
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customers::class,  // Correct model for customers
            'table' => 'customers', // Table name for customers
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,  // Correct model for admins
            'table' => 'admins', // Table name for admins
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Specify behavior of password reset functionality.
    |
    */

    'passwords' => [
        'customers' => [
            'provider' => 'customers',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Define the timeout for password confirmation window.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
