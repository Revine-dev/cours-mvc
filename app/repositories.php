<?php

declare(strict_types=1);

use App\Entity\User\UserRepository;
use App\Infrastructure\Persistence\User\DoctrineUserRepository;
use App\Entity\Property\PropertyRepository;
use App\Infrastructure\Persistence\Property\DoctrinePropertyRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(DoctrineUserRepository::class),
        PropertyRepository::class => \DI\autowire(DoctrinePropertyRepository::class),
    ]);
};
