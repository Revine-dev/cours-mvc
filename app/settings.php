<?php

declare(strict_types=1);

use App\Application\Config\ConfigRegistry;
use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => ConfigRegistry::get("debug") && LOCAL,
                'logError'            => ConfigRegistry::get("debug"),
                'logErrorDetails'     => ConfigRegistry::get("debug"),
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : ROOT . '/logs/app.log',
                    'level' => Logger::DEBUG,
                ],
            ]);
        }
    ]);
};
