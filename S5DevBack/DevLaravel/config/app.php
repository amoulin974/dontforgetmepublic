<?php

/**
 * @file app.php
 * @brief Application configuration file.
 *
 * This file returns an array of settings used by the Laravel application.
 * It contains configurations for the application name, environment, debug mode, URL,
 * timezone, locale, encryption keys, maintenance mode, and more.
 */

return [

    /**
     * @brief Application Name.
     *
     * This value is the name of your application, which will be used when the
     * framework needs to display the application's name in notifications or other UI elements.
     *
     * @var string
     */
    'name' => env('APP_NAME', 'DFM'),

    /**
     * @brief Application Environment.
     *
     * This value determines the environment your application is currently running in.
     * It may determine how you configure various services. Set this in your ".env" file.
     *
     * @var string
     */
    'env' => env('APP_ENV', 'production'),

    /**
     * @brief Application Debug Mode.
     *
     * When enabled, detailed error messages with stack traces will be shown on every error that occurs
     * within your application. If disabled, a simple generic error page is shown.
     *
     * @var bool
     */
    'debug' => (bool) env('APP_DEBUG', false),

    /**
     * @brief Application URL.
     *
     * This URL is used by the console to properly generate URLs when using the Artisan command line tool.
     * Set this to the root of your application.
     *
     * @var string
     */
    'url' => env('APP_URL', 'http://localhost'),

    /**
     * @brief Application Timezone.
     *
     * Here you may specify the default timezone for your application, which will be used by the PHP
     * date and date-time functions.
     *
     * @var string
     */
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /**
     * @brief Application Locale Configuration.
     *
     * This option determines the default locale that will be used by Laravel's translation and
     * localization methods.
     *
     * @var string
     */
    'locale' => env('APP_LOCALE', 'fr'),

    /**
     * @brief Fallback Locale.
     *
     * The locale to use when the current one is not available.
     *
     * @var string
     */
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    /**
     * @brief Faker Locale.
     *
     * The locale used by the Faker PHP library when generating fake data.
     *
     * @var string
     */
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /**
     * @brief Encryption Cipher.
     *
     * The cipher that should be used by Laravel's encryption services.
     *
     * @var string
     */
    'cipher' => 'AES-256-CBC',

    /**
     * @brief Application Encryption Key.
     *
     * This key is used by Laravel's encrypter service and should be set to a random, 32 character string.
     *
     * @var string|null
     */
    'key' => env('APP_KEY'),

    /**
     * @brief Previous Encryption Keys.
     *
     * These keys are used to decrypt data that was encrypted with old keys.
     *
     * @var array
     */
    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /**
     * @brief Maintenance Mode Configuration.
     *
     * These options determine the driver used to manage Laravel's maintenance mode status.
     * The "cache" driver will allow maintenance mode to be controlled across multiple machines.
     *
     * Supported drivers: "file", "cache".
     *
     * @var array
     */
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store'  => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
