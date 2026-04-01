<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\JsonPropertyUserRepository;
use App\Domain\Property\PropertyRepository;
use App\Infrastructure\Persistence\Property\JsonPropertyRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(JsonPropertyUserRepository::class),
        PropertyRepository::class => \DI\autowire(JsonPropertyRepository::class),
    ]);
};
