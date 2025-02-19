<?php

/**
 * @file cache.php
 * @brief Cache configuration file.
 *
 * This file defines caching settings, including the default cache store,
 * available cache drivers, and key prefixing to prevent conflicts.
 */

use Illuminate\Support\Str;

return [

    /**
     * @brief Default Cache Store.
     *
     * Defines the default cache store used by the framework when no specific
     * store is mentioned during cache operations.
     *
     * @var string
     */
    'default' => env('CACHE_STORE', 'database'),

    /**
     * @brief Cache Stores.
     *
     * Lists all available cache stores and their configurations. Multiple stores
     * can be defined for the same cache driver.
     *
     * Supported drivers: "array", "database", "file", "memcached",
     *                    "redis", "dynamodb", "octane", "null"
     *
     * @var array
     */
    'stores' => [

        'array' => [
            'driver' => 'array', ///< Non-persistent in-memory cache
            'serialize' => false, ///< Determines if values should be serialized
        ],

        'database' => [
            'driver' => 'database', ///< Stores cache data in a database table
            'connection' => env('DB_CACHE_CONNECTION'), ///< Database connection name
            'table' => env('DB_CACHE_TABLE', 'cache'), ///< Cache table name
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'), ///< Locking mechanism connection
            'lock_table' => env('DB_CACHE_LOCK_TABLE'), ///< Locking table name
        ],

        'file' => [
            'driver' => 'file', ///< Stores cache data as files
            'path' => storage_path('framework/cache/data'), ///< File storage path
            'lock_path' => storage_path('framework/cache/data'), ///< Lock file path
        ],

        'memcached' => [
            'driver' => 'memcached', ///< Uses Memcached for caching
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'), ///< Persistent connection identifier
            'sasl' => [
                env('MEMCACHED_USERNAME'), ///< Authentication username
                env('MEMCACHED_PASSWORD'), ///< Authentication password
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'), ///< Memcached server host
                    'port' => env('MEMCACHED_PORT', 11211), ///< Memcached server port
                    'weight' => 100, ///< Server weight for load balancing
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis', ///< Uses Redis for caching
            'connection' => env('REDIS_CACHE_CONNECTION', 'cache'), ///< Redis connection name
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'), ///< Locking mechanism connection
        ],

        'dynamodb' => [
            'driver' => 'dynamodb', ///< Uses AWS DynamoDB for caching
            'key' => env('AWS_ACCESS_KEY_ID'), ///< AWS access key
            'secret' => env('AWS_SECRET_ACCESS_KEY'), ///< AWS secret key
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'), ///< AWS region
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'), ///< DynamoDB table name
            'endpoint' => env('DYNAMODB_ENDPOINT'), ///< Custom endpoint (if any)
        ],

        'octane' => [
            'driver' => 'octane', ///< Uses Laravel Octane for caching
        ],

    ],

    /**
     * @brief Cache Key Prefix.
     *
     * Used to prevent key collisions when multiple applications share
     * the same caching system.
     *
     * @var string
     */
    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'),

];
