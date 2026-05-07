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
        $em = $this->getEntityManager();
        $this->repository = new DoctrinePropertyRepository($em, $em->getClassMetadata(Property::class));
    }

    private function createSampleProperty(string $title, string $slug, float $price = 100000.0, string $city = 'Paris'): Property
    {
        $property = new Property();
        $property->title = $title;
        $property->slug = $slug;

        $location = new Location();
        $location->city = $city;
        $location->address = '123 Test St';
        $location->postal_code = '75000';
        $location->country = 'France';

        // Set protected properties via reflection
        $reflection = new \ReflectionClass($property);

        $locationProp = $reflection->getProperty('location');
        $locationProp->setAccessible(true);
        $locationProp->setValue($property, $location);

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
        $property = $this->createSampleProperty('Beach House', 'beach-house', 100000.0, 'Marseille');
        $this->em->persist($property);
        $this->em->flush();

        $foundProperty = $this->repository->findPropertyOfSlug('beach-house', 'Marseille');
        $this->assertEquals('Beach House', $foundProperty->title);
    }

    public function testSearch(): void
    {
        $p1 = $this->createSampleProperty('Modern Loft', 'modern-loft', 150000.0, 'Paris');
        $p2 = $this->createSampleProperty('Old Cottage', 'old-cottage', 80000.0, 'Lyon');
        $p1->status = 'for_sale';
        $p2->status = 'for_sale';

        $this->em->persist($p1);
        $this->em->persist($p2);
        $this->em->flush();

        // Test city filter
        $results = $this->repository->search(['city' => 'Lyon']);
        $this->assertCount(1, $results['items']);
        $this->assertEquals('Old Cottage', $results['items'][0]->title);

        // Test price filter
        $results = $this->repository->search(['min_price' => 100000]);
        $this->assertCount(1, $results['items']);
        $this->assertEquals('Modern Loft', $results['items'][0]->title);

        // Test multiple filters
        $results = $this->repository->search(['status_in' => ['for_sale']]);
        $this->assertCount(2, $results['items']);
    }

    public function testSearchPagination(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $p = $this->createSampleProperty("Prop $i", "prop-$i", 100000.0 * $i);
            $p->status = 'for_sale';
            $this->em->persist($p);
        }
        $this->em->flush();

        // Page 1, 2 items per page
        $pagination = $this->repository->search([], 1, 2);
        $this->assertCount(2, $pagination['items']);
        $this->assertEquals(5, $pagination['total']);
        $this->assertEquals(1, $pagination['current_page']);
        $this->assertEquals(2, $pagination['per_page']);
        $this->assertEquals(3, $pagination['last_page']);
    }
}
