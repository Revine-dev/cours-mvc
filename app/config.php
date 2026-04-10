<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database Host
    |--------------------------------------------------------------------------
    |
    | The hostname of your database server.
    | Usually "localhost" for local development.
    |
    */
    'db_host' => getenv('DB_HOST') ?: '127.0.0.1',


    /*
    |--------------------------------------------------------------------------
    | Database Name
    |--------------------------------------------------------------------------
    |
    | The name of the database you want to connect to.
    |
    */
    'db_name' => getenv('DB_NAME') ?: 'cours_mvc',


    /*
    |--------------------------------------------------------------------------
    | Database Username
    |--------------------------------------------------------------------------
    |
    | Your database username.
    | Default is "root" for many local setups.
    |
    */
    'db_user' => getenv('DB_USER') ?: 'root',


    /*
    |--------------------------------------------------------------------------
    | Database Password
    |--------------------------------------------------------------------------
    |
    | Your database password.
    | Default password: "root".
    |
    */
    'db_pass' => getenv('DB_PASS') ?: 'root',


    /*
    |--------------------------------------------------------------------------
    | Database Port
    |--------------------------------------------------------------------------
    |
    | The port used by your database server.
    | Default MySQL port is 3306.
    |
    */
    'db_port' => getenv('DB_PORT') ?: 3306,


    /*
    |--------------------------------------------------------------------------
    | Database Charset
    |--------------------------------------------------------------------------
    |
    | The charset
    | Default MySQL charset is utf8mb4.
    |
    */
    'db_charset' => getenv('DB_CHARSET') ?: 'utf8mb4',


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

];
