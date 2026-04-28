<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, detailed error messages will be shown.
    | Should be disabled in production environments.
    |
    */
    'debug' => filter_var(getenv('DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN),


    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | The current environment (dev, prod).
    |
    */
    'env' => getenv('ENV') ?: 'prod',


    /*
    |--------------------------------------------------------------------------
    | HTTP Error Logging
    |--------------------------------------------------------------------------
    |
    | Enable or disable logging of HTTP-related errors.
    |
    */
    'log_http_errors' => false,

    /*
    |--------------------------------------------------------------------------
    | Doctrine Migrations Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Doctrine Migrations.
    |
    */
    'migrations' => [
        'table_storage' => [
            'table_name' => 'doctrine_migration_versions',
            'version_column_name' => 'version',
            'version_column_length' => 255,
            'executed_at_column_name' => 'executed_at',
            'execution_time_column_name' => 'execution_time',
        ],

        'migrations_paths' => [
            'App\Migrations' => ROOT . '/src/Migrations',
        ],

        'all_or_nothing' => true,
        'transactional' => true,
        'check_database_platform' => true,
        'organize_migrations' => 'none',
    ],

    /*
    |--------------------------------------------------------------------------
    | Slim Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Slim Framework and other components.
    |
    */
    'settings' => [
        'displayErrorDetails' => filter_var(getenv('DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN) && LOCAL,
        'logError'            => filter_var(getenv('DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN),
        'logErrorDetails'     => filter_var(getenv('DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN),
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : ROOT . '/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => ROOT . '/var/cache/doctrine',
            'metadata_dirs' => [ROOT . '/src/Entity'],
            'connection' => [
                'driver'   => 'pdo_mysql',

                /*
                | The hostname of your database server.
                | Usually "localhost" for local development.
                */
                'host'     => getenv('DB_HOST') ?: '127.0.0.1',

                /*
                | The port used by your database server.
                | Default MySQL port is 3306.
                */
                'port'     => (int)(getenv('DB_PORT') ?: 3306),

                /*
                | The name of the database you want to connect to.
                */
                'dbname'   => getenv('DB_NAME') ?: 'cours_mvc',

                /*
                | Your database username.
                | Default is "root" for many local setups.
                */
                'user'     => getenv('DB_USER') ?: 'root',

                /*
                | Your database password.
                | Default password: "root".
                */
                'password' => getenv('DB_PASS') ?: 'root',

                /*
                | The charset
                | Default MySQL charset is utf8mb4.
                */
                'charset'  => getenv('DB_CHARSET') ?: 'utf8mb4',
            ],
        ],
    ],

];
