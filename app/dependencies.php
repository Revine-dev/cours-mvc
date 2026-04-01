<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Application\Helpers\Helper;
use App\Application\Helpers\StringHelper;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Helper::class => function (ContainerInterface $c) {
            return new Helper($c);
        },
        StringHelper::class => function () {
            return new StringHelper();
        },
        UserHelper::class => \DI\autowire(UserHelper::class),
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
