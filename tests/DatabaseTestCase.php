<?php

declare(strict_types=1);

namespace Tests;

use App\Application\Settings\SettingsInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Psr\Container\ContainerInterface;

abstract class DatabaseTestCase extends TestCase
{
    protected ?EntityManager $em = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function tearDown(): void
    {
        if ($this->em) {
            $this->em->close();
            $this->em = null;
        }
        parent::tearDown();
    }

    private function setUpDatabase(): void
    {
        $app = $this->getAppInstance();
        /** @var ContainerInterface $container */
        $container = $app->getContainer();

        $settings = $container->get(SettingsInterface::class)->get('doctrine');

        $config = ORMSetup::createAttributeMetadataConfiguration(
            $settings['metadata_dirs'],
            true, // dev mode
            $settings['cache_dir'] . '/proxy'
        );

        $connectionParams = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $connection = DriverManager::getConnection($connectionParams, $config);
        $this->em = new EntityManager($connection, $config);

        // Create Schema
        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    protected function getEntityManager(): EntityManager
    {
        return $this->em;
    }
}
