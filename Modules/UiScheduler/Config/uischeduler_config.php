<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Scheduler Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the default scheduler that will be used by the
    | application. You may set this to 'laravel' or 'crunz'.
    |
    */

    'scheduler' => env('UISCHEDULER_SCHEDULER', 'laravel'),

    /*
    |--------------------------------------------------------------------------
    | Mutex Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the default mutex that will be used by the
    | application. You may set this to 'file' or 'redis'.
    |
    */

    'mutex' => env('UISCHEDULER_MUTEX', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Redis Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your settings for Redis, which will be used
    | if you have selected 'redis' as your mutex type.
    |
    */

    'redis' => [
        'scheme' => env('UISCHEDULER_REDIS_SCHEME', 'tcp'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'port' => env('REDIS_PORT', 6379),
    ],

];
