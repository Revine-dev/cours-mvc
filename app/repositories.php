<?php

declare(strict_types=1);

use App\Entity\User\User;
use App\Entity\User\UserRepository;
use App\Infrastructure\Persistence\User\DoctrineUserRepository;
use App\Entity\Property\Property;
use App\Entity\Property\PropertyRepository;
use App\Infrastructure\Persistence\Property\DoctrinePropertyRepository;
use App\Entity\Agent\Agent;
use App\Entity\Agent\AgentRepository;
use App\Infrastructure\Persistence\Agent\DoctrineAgentRepository;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => function (ContainerInterface $c) {
            $em = $c->get(EntityManager::class);
            return new DoctrineUserRepository($em, $em->getClassMetadata(User::class));
        },
        PropertyRepository::class => function (ContainerInterface $c) {
            $em = $c->get(EntityManager::class);
            return new DoctrinePropertyRepository($em, $em->getClassMetadata(Property::class));
        },
        AgentRepository::class => function (ContainerInterface $c) {
            $em = $c->get(EntityManager::class);
            return new DoctrineAgentRepository($em, $em->getClassMetadata(Agent::class));
        },
    ]);
};
