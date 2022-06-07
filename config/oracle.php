<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS', ''),
        'host'           => env('DB_HOST', ''),
        'port'           => env('DB_PORT', '1521'),
        'database'       => env('DB_DATABASE', ''),
        'username'       => env('DB_USERNAME', ''),
        'password'       => env('DB_PASSWORD', ''),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
        /* 'options' => [
            PDO::ATTR_CASE => PDO::CASE_UPPER,
        ] */
    ],
    'subalia' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS2', ''),
        'host'           => env('DB_HOST', ''),
        'port'           => env('DB_PORT', '1521'),
        'database'       => env('DB_DATABASE', ''),
        'username'       => env('DB_USERNAME', ''),
        'password'       => env('DB_PASSWORD', ''),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
    ],
];

/**
 * Tambien funciona:
 * 'oracle' => [
 *      'driver'         => 'oracle',
 *      'host'           => env('DB_HOST', ''),
 *      'port'           => env('DB_PORT', '1521'),
 *	 	'database'       => env('DB_DATABASE', ''),
*		'service_name'	 => env('DB_SERVICENAME', ''),
*        'username'       => env('DB_USERNAME', ''),
*        'password'       => env('DB_PASSWORD', ''),
*        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
*        'prefix'         => env('DB_PREFIX', ''),
*        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
*        'edition'        => env('DB_EDITION', 'ora$base'),
*        'server_version' => env('DB_SERVER_VERSION', '11g'),
*    ]
 * donde en env las variables son:
 * DB_HOST='demosub.label-grup.com'
 * DB_PORT='1521'
 * DB_DATABASE='xe'
 * DB_SERVICENAME='xe'
 *
 */
