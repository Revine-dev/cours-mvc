<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Property;

use App\Entity\Property\Property;
use App\Entity\Agent\Agent;
use App\Entity\Location\Location;
use App\Infrastructure\Persistence\Property\DoctrinePropertyRepository;
use Tests\DatabaseTestCase;

class DoctrinePropertyRepositoryTest extends DatabaseTestCase
{
    private DoctrinePropertyRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DoctrinePropertyRepository($this->getEntityManager());
    }

    private function createSampleProperty(string $title, string $slug, float $price = 100000.0): Property
    {
        $property = new Property();
        $property->title = $title;
        $property->slug = $slug;

        // Nullify auto-initialized non-persisted entities to avoid Doctrine errors
        $reflection = new \ReflectionClass($property);

        $locationProp = $reflection->getProperty('location');
        $locationProp->setAccessible(true);
        $locationProp->setValue($property, null);

        $agentProp = $reflection->getProperty('agent');
        $agentProp->setAccessible(true);
        $agentProp->setValue($property, null);

        $priceProp = $reflection->getProperty('price');
        $priceProp->setAccessible(true);
        $priceProp->setValue($property, (string)$price);

        $property->status = 'Available';
        $property->type = 'House';

        return $property;
    }

    public function testFindAll(): void
    {
        $property = $this->createSampleProperty('Luxury Villa', 'luxury-villa');
        $this->em->persist($property);
        $this->em->flush();

        $properties = $this->repository->findAll();
        $this->assertCount(1, $properties);
        $this->assertEquals('Luxury Villa', $properties[0]->title);
    }

    public function testFindPropertyOfId(): void
    {
        $property = $this->createSampleProperty('Cozy Apartment', 'cozy-apartment');
        $this->em->persist($property);
        $this->em->flush();

        $foundProperty = $this->repository->findPropertyOfId($property->id);
        $this->assertEquals('Cozy Apartment', $foundProperty->title);
    }

    public function testFindPropertyOfSlug(): void
    {
        $property = $this->createSampleProperty('Beach House', 'beach-house');
        $this->em->persist($property);
        $this->em->flush();

        $foundProperty = $this->repository->findPropertyOfSlug('beach-house');
        $this->assertEquals('Beach House', $foundProperty->title);
    }

    public function testWhereLike(): void
    {
        $p1 = $this->createSampleProperty('Modern Loft', 'modern-loft');
        $p2 = $this->createSampleProperty('Old Cottage', 'old-cottage');

        $this->em->persist($p1);
        $this->em->persist($p2);
        $this->em->flush();

        $results = $this->repository->whereLike('title', 'Modern')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('Modern Loft', $results[0]->title);
    }

    public function testPriceFilters(): void
    {
        $p1 = $this->createSampleProperty('Cheap', 'cheap', 50000.0);
        $p2 = $this->createSampleProperty('Expensive', 'expensive', 500000.0);

        $this->em->persist($p1);
        $this->em->persist($p2);
        $this->em->flush();

        $cheapOnes = $this->repository->whereLessThanOrEqual('price', 100000.0)->get();
        $this->assertCount(1, $cheapOnes);
        $this->assertEquals('Cheap', $cheapOnes[0]->title);

        $expensiveOnes = $this->repository->whereGreaterThanOrEqual('price', 400000.0)->get();
        $this->assertCount(1, $expensiveOnes);
        $this->assertEquals('Expensive', $expensiveOnes[0]->title);
    }
}
