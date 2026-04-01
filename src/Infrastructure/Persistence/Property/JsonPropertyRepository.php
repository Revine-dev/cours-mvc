<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Property;

use App\Domain\Property\Property;
use App\Domain\Property\PropertyNotFoundException;
use App\Domain\Property\PropertyRepository;
use App\Infrastructure\Persistence\JsonRepository;

class JsonPropertyRepository extends JsonRepository implements PropertyRepository
{
    public function __construct()
    {
        parent::__construct('properties', Property::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(string $key, mixed $value): ?Property
    {
        return parent::findOneBy($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function findPropertyOfId(int $id): Property
    {
        $property = $this->findOneBy('id', $id);
        if (!$property) throw new PropertyNotFoundException();
        return $property;
    }

    /**
     * {@inheritdoc}
     */
    public function findPropertyOfSlug(string $slug): Property
    {
        $property = $this->findOneBy('slug', $slug);
        if (!$property) throw new PropertyNotFoundException();
        return $property;
    }
}
