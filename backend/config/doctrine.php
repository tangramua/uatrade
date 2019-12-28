<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Entity Mangers
    |--------------------------------------------------------------------------
    |
    | Configure your Entity Managers here. You can set a different connection
    | and driver per manager and configure events and filters. Change the
    | paths setting to the appropriate path and replace App namespace
    | by your own namespace.
    |
    | Available meta drivers: fluent|annotations|yaml|xml|config|static_php|php
    |
    | Available connections: mysql|oracle|pgsql|sqlite|sqlsrv
    | (Connections can be configured in the database config)
    |
    | --> Warning: Proxy auto generation should only be enabled in dev!
    |
    */
    'managers'                   => [
        'default' => [
            'dev'           => env('APP_DEBUG', false),
            'meta'          => env('DOCTRINE_METADATA', 'xml'),
            'connection'    => env('DB_CONNECTION', 'mysql'),
            'namespaces'    => [
                base_path('modules/Auth/Entities/Doctrine') => 'Modules/Auth/Entities/Doctrine',
                base_path('modules/Core/Entities/Doctrine') => 'Modules/Core/Entities/Doctrine',
                base_path('modules/User/Entities/Doctrine') => 'Modules/User/Entities/Doctrine',
                base_path('modules/GeoNames/Entities/Doctrine') => 'Modules/GeoNames/Entities/Doctrine',
                base_path('modules/Company/Entities/Doctrine') => 'Modules/Company/Entities/Doctrine',
                base_path('modules/Location/Entities/Doctrine') => 'Modules/Location/Entities/Doctrine',
                base_path('modules/Event/Entities/Doctrine') => 'Modules/Event/Entities/Doctrine',
                base_path('modules/Visitor/Entities/Doctrine') => 'Modules/Visitor/Entities/Doctrine',
                base_path('modules/Wechat/Entities/Doctrine') => 'Modules/Wechat/Entities/Doctrine',
                base_path('modules/RocketChat/Entities/Doctrine') => 'Modules/RocketChat/Entities/Doctrine',
                base_path('modules/Notification/Entities/Doctrine') => 'Modules/Notification/Entities/Doctrine',
                base_path('modules/CMS/Entities/Doctrine') => 'Modules/CMS/Entities/Doctrine',
                base_path('modules/Testimonials/Entities/Doctrine') => 'Modules/Testimonials/Entities/Doctrine',
            ],
            'paths'         => [
                "Modules/Auth/Entities/Doctrine" => base_path('modules/Auth/Entities/Doctrine/mappings'),
                "Modules/Core/Entities/Doctrine" => base_path('modules/Core/Entities/Doctrine/mappings'),
                "Modules/GeoNames/Entities/Doctrine" => base_path('modules/GeoNames/Entities/Doctrine/mappings'),
                "Modules/User/Entities/Doctrine" => base_path('modules/User/Entities/Doctrine/mappings'),
                "Modules/User/Entities/Doctrine/User" => base_path('modules/User/Entities/Doctrine/mappings'),
                "Modules/Company/Entities/Doctrine" => base_path('modules/Company/Entities/Doctrine/mappings'),
                "Modules/Location/Entities/Doctrine" => base_path('modules/Location/Entities/Doctrine/mappings'),
                "Modules/Event/Entities/Doctrine" => base_path('modules/Event/Entities/Doctrine/mappings'),
                "Modules/Visitor/Entities/Doctrine" => base_path('modules/Visitor/Entities/Doctrine/mappings'),
                "Modules/Wechat/Entities/Doctrine" => base_path('modules/Wechat/Entities/Doctrine/mappings'),
                "Modules/RocketChat/Entities/Doctrine" => base_path('modules/RocketChat/Entities/Doctrine/mappings'),
                "Modules/Notification/Entities/Doctrine/ContactUs" => base_path('modules/Notification/Entities/Doctrine/mappings/ContactUs'),
                "Modules/Notification/Entities/Doctrine/WechatGroup" => base_path('modules/Notification/Entities/Doctrine/mappings/WechatGroup'),
                "Modules/CMS/Entities/Doctrine" => base_path('modules/CMS/Entities/Doctrine/mappings'),
                "Modules/Testimonials/Entities/Doctrine" => base_path('modules/Testimonials/Entities/Doctrine/mappings'),
            ],
//            'repository'    => Doctrine\ORM\EntityRepository::class,
            'repository'    => Modules\Core\Repositories\Doctrine\EntityRepository::class,
            'proxies'       => [
                'namespace'     => false,
                'path'          => storage_path('proxies'),
                'auto_generate' => env('DOCTRINE_PROXY_AUTOGENERATE', false)
            ],
            /*
            |--------------------------------------------------------------------------
            | Doctrine events
            |--------------------------------------------------------------------------
            |
            | The listener array expects the key to be a Doctrine event
            | e.g. Doctrine\ORM\Events::onFlush
            |
            */
            'events'        => [
                'listeners'   => [],
                'subscribers' => []
            ],
            'filters'       => [],
            /*
            |--------------------------------------------------------------------------
            | Doctrine mapping types
            |--------------------------------------------------------------------------
            |
            | Link a Database Type to a Local Doctrine Type
            |
            | Using 'enum' => 'string' is the same of:
            | $doctrineManager->extendAll(function (\Doctrine\ORM\Configuration $configuration,
            |         \Doctrine\DBAL\Connection $connection,
            |         \Doctrine\Common\EventManager $eventManager) {
            |     $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
            | });
            |
            | References:
            | http://doctrine-orm.readthedocs.org/en/latest/cookbook/custom-mapping-types.html
            | http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html#custom-mapping-types
            | http://doctrine-orm.readthedocs.org/en/latest/cookbook/advanced-field-value-conversion-using-custom-mapping-types.html
            | http://doctrine-orm.readthedocs.org/en/latest/reference/basic-mapping.html#reference-mapping-types
            | http://symfony.com/doc/current/cookbook/doctrine/dbal.html#registering-custom-mapping-types-in-the-schematool
            |--------------------------------------------------------------------------
            */
            'mapping_types' => [
                //'enum' => 'string'
            ]
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine Extensions
    |--------------------------------------------------------------------------
    |
    | Enable/disable Doctrine Extensions by adding or removing them from the list
    |
    | If you want to require custom extensions you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'extensions'                 => [
        //Modules\Core\Extensions\Doctrine\DistinctFixerExtension::class,
        //LaravelDoctrine\ORM\Extensions\TablePrefix\TablePrefixExtension::class,
        LaravelDoctrine\Extensions\Timestamps\TimestampableExtension::class,
        LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension::class,
        //LaravelDoctrine\Extensions\Sluggable\SluggableExtension::class,
        //LaravelDoctrine\Extensions\Sortable\SortableExtension::class,
        //LaravelDoctrine\Extensions\Tree\TreeExtension::class,
        //LaravelDoctrine\Extensions\Loggable\LoggableExtension::class,
        //LaravelDoctrine\Extensions\Blameable\BlameableExtension::class,
        //LaravelDoctrine\Extensions\IpTraceable\IpTraceableExtension::class,
        //LaravelDoctrine\Extensions\Translatable\TranslatableExtension::class, //broken on all logic is moved our own
        Modules\Core\Extensions\Doctrine\TranslatableExtension::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine custom types
    |--------------------------------------------------------------------------
    |
    | Create a custom or override a Doctrine Type
    |--------------------------------------------------------------------------
    */
    'custom_types'               => [
        'json' => LaravelDoctrine\ORM\Types\Json::class
    ],
    /*
    |--------------------------------------------------------------------------
    | DQL custom datetime functions
    |--------------------------------------------------------------------------
    */
    'custom_datetime_functions'  => [],
    /*
    |--------------------------------------------------------------------------
    | DQL custom numeric functions
    |--------------------------------------------------------------------------
    */
    'custom_numeric_functions'   => [],
    /*
    |--------------------------------------------------------------------------
    | DQL custom string functions
    |--------------------------------------------------------------------------
    */
    'custom_string_functions'    => [],
    /*
    |--------------------------------------------------------------------------
    | Register custom hydrators
    |--------------------------------------------------------------------------
    */
    'custom_hydration_modes'     => [
        // e.g. 'hydrationModeName' => MyHydrator::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Enable query logging with laravel file logging,
    | debugbar, clockwork or an own implementation.
    | Setting it to false, will disable logging
    |
    | Available:
    | - LaravelDoctrine\ORM\Loggers\LaravelDebugbarLogger
    | - LaravelDoctrine\ORM\Loggers\ClockworkLogger
    | - LaravelDoctrine\ORM\Loggers\FileLogger
    |--------------------------------------------------------------------------
    */
    'logger'                     => env('DOCTRINE_LOGGER', false),
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configure meta-data, query and result caching here.
    | Optionally you can enable second level caching.
    |
    | Available: apc|array|file|memcached|redis|void
    |
    */
    'cache' => [
        'second_level'     => false,
        'default'          => env('DOCTRINE_CACHE', 'array'),
        'namespace'        => null,
        'metadata'         => [
            'driver'       => env('DOCTRINE_METADATA_CACHE', env('DOCTRINE_CACHE', 'array')),
            'namespace'    => null,
        ],
        'query'            => [
            'driver'       => env('DOCTRINE_QUERY_CACHE', env('DOCTRINE_CACHE', 'array')),
            'namespace'    => null,
        ],
        'result'           => [
            'driver'       => env('DOCTRINE_RESULT_CACHE', env('DOCTRINE_CACHE', 'array')),
            'namespace'    => null,
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Gedmo extensions
    |--------------------------------------------------------------------------
    |
    | Settings for Gedmo extensions
    | If you want to use this you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'gedmo'                      => [
        'all_mappings' => false
    ],
    /*
     |--------------------------------------------------------------------------
     | Validation
     |--------------------------------------------------------------------------
     |
     |  Enables the Doctrine Presence Verifier for Validation
     |
     */
    'doctrine_presence_verifier' => true,

    /*
     |--------------------------------------------------------------------------
     | Notifications
     |--------------------------------------------------------------------------
     |
     |  Doctrine notifications channel
     |
     */
    'notifications'              => [
        'channel' => 'database'
    ]
];
