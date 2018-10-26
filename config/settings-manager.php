<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Deafult Bag name
    |--------------------------------------------------------------------------
    |
    | This config is use to determine defult Bag (string).
    |
    */

    'default'   => 'general',

    /*
    |--------------------------------------------------------------------------
    | Bags
    |--------------------------------------------------------------------------
    |
    | These configuration allows you to set all needed Bags with different
    | cache configuration and driver.
    |
    */

    'bags' => [

        'general' => [
            'driver'   => 'database',
            'cache'    => true,
            'lifetime' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    |
    | These configuration allows you to set different drivers for application
    | usage. You can use one of two build in Driver classes:
    |
    | Database SQL: \Mrluke\Settings\Drivers\Database::class
    |               connection & table properties required
    |
    | JSON:         \Mrluke\Settings\Drivers\Json::class
    |               path & file properties required
    |
    */

    'drivers' => [

        'database' => [
            'class'      => \Mrluke\Settings\Drivers\Database::class,
            'connection' => 'mysql',
            'table'      => 'settings',
        ],

    ],

];
