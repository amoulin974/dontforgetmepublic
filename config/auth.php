<?php

/**
 * @file auth.php
 * @brief Authentication configuration file.
 *
 * This file defines authentication settings such as guards, user providers,
 * password reset policies, and other security-related configurations.
 */

return [

    /**
     * @brief Authentication Defaults.
     *
     * Defines the default authentication "guard" and password reset "broker".
     * These values can be modified as needed.
     *
     * @var array
     */
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),  ///< Default authentication guard
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'), ///< Default password reset broker
    ],

    /**
     * @brief Authentication Guards.
     *
     * Defines different authentication guards. Guards use user providers
     * to retrieve user information and handle authentication.
     *
     * @var array
     */
    'guards' => [
        'web' => [
            'driver' => 'session', ///< Uses session-based authentication
            'provider' => 'users', ///< Refers to the 'users' provider
        ],
    ],

    /**
     * @brief User Providers.
     *
     * Specifies how users are retrieved from storage (database, Eloquent model, etc.).
     *
     * @var array
     */
    'providers' => [
        'users' => [
            'driver' => 'eloquent', ///< Uses Eloquent ORM for user retrieval
            'model' => env('AUTH_MODEL', App\Models\User::class), ///< Defines the User model class
        ],

        // Alternative database-based authentication (commented out by default)
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users', ///< Retrieves users directly from database table
        // ],
    ],

    /**
     * @brief Resetting Passwords.
     *
     * Configures password reset settings, including expiration and throttling.
     *
     * @var array
     */
    'passwords' => [
        'users' => [
            'provider' => 'users', ///< Links to the 'users' provider
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'), ///< Token storage table
            'expire' => 60, ///< Password reset token validity in minutes
            'throttle' => 60, ///< Minimum wait time (seconds) before generating new reset tokens
        ],
    ],

    /**
     * @brief Password Confirmation Timeout.
     *
     * Defines how long (in seconds) a password confirmation remains valid.
     *
     * @var int
     */
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800), ///< Default: 3 hours
];
