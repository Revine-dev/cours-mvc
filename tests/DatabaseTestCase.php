<?php

declare(strict_types=1);

namespace Tests;

use App\Application\Settings\SettingsInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Psr\Container\ContainerInterface;
use Slim\App;

abstract class DatabaseTestCase extends TestCase
{
    protected ?EntityManager $em = null;

    protected function tearDown(): void
    {
        if ($this->em) {
            $this->em->close();
            $this->em = null;
        }
        $_SESSION = [];
        parent::tearDown();
    }

    protected ?App $app = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    private function setUpDatabase(): void
    {
        // First, get a temporary app to read settings
        $tempApp = $this->getAppInstance();
        $container = $tempApp->getContainer();
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

        // Now create the real app instance with the overridden EM
        $this->app = $this->getAppInstance([
            EntityManager::class => $this->em
        ]);
    }

    protected function getAppInstance(array $overrides = []): App
    {
        if ($this->app !== null && empty($overrides)) {
            return $this->app;
        }
        return parent::getAppInstance($overrides);
    }

    protected function getEntityManager(): EntityManager
    {
        return $this->em;
    }
}
