<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Application\Settings\Settings;
use App\Application\Config\ConfigRegistry;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Application\Helpers\Helper;
use App\Application\Helpers\StringHelper;
use App\Application\Helpers\UserHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings(ConfigRegistry::get('settings'));
        },
        EntityManager::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class)->get('doctrine');
            $config = ORMSetup::createAttributeMetadataConfiguration(
                $settings['metadata_dirs'],
                $settings['dev_mode'],
                $settings['cache_dir'] . '/proxy'
            );

            // Ignore doctrine_migration_versions table in schema validation
            $config->setSchemaAssetsFilter(function ($assetName) {
                return !in_array($assetName, ['doctrine_migration_versions']);
            });

            $connection = DriverManager::getConnection($settings['connection'], $config);
            return new EntityManager($connection, $config);
        },
        Helper::class => function (ContainerInterface $c) {
            return new Helper($c);
        },
        StringHelper::class => function () {
            return new StringHelper();
        },
        UserHelper::class => \DI\autowire(UserHelper::class),
        \App\Application\Helpers\SecurityHelper::class => \DI\autowire(\App\Application\Helpers\SecurityHelper::class),
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $formatter = new \Monolog\Formatter\LineFormatter(null, null, true, true);
            $handler->setFormatter($formatter);
            $logger->pushHandler($handler);

            return $logger;
        },
    ]);
};
