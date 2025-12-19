<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    |
    | The authentication guard to use for protected routes.
    |
    */
    'guard' => env('ASTROXS_GUARD', 'sanctum'),

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | The URL prefix for all Astroxs routes.
    |
    */
    'route_prefix' => env('ASTROXS_ROUTE_PREFIX', 'api'),

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware applied to all Astroxs routes.
    |
    */
    'route_middleware' => ['api'],

    /*
    |--------------------------------------------------------------------------
    | Default User Role
    |--------------------------------------------------------------------------
    |
    | The default role slug assigned to new user registrations.
    |
    */
    'default_user_role_slug' => env('ASTROXS_DEFAULT_ROLE', 'user'),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Enable caching of roles and privileges for performance.
    |
    */
    'cache' => [
        'enabled' => env('ASTROXS_CACHE_ENABLED', true),
        'ttl' => env('ASTROXS_CACHE_TTL', 3600), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Disable API Routes
    |--------------------------------------------------------------------------
    |
    | Set to true to disable all built-in API routes.
    |
    */
    'disable_api' => env('ASTROXS_DISABLE_API', false),

    /*
    |--------------------------------------------------------------------------
    | Disable Commands
    |--------------------------------------------------------------------------
    |
    | Set to true to disable all artisan commands (recommended for production).
    |
    */
    'disable_commands' => env('ASTROXS_DISABLE_COMMANDS', false),

    /*
    |--------------------------------------------------------------------------
    | Log Channel
    |--------------------------------------------------------------------------
    |
    | The log channel to use for the astroxs.log middleware.
    |
    */
    'log_channel' => env('ASTROXS_LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Protected Roles
    |--------------------------------------------------------------------------
    |
    | Role slugs that cannot be deleted or renamed.
    |
    */
    'protected_roles' => [
        'super-admin',
        'admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Current version of the Astroxs package.
    |
    */
    'version' => '1.0.0',
];
