<?php

declare(strict_types=1);

namespace App\Entity\Property;

interface PropertyRepository
{
    /**
     * @return Property[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Property
     */
    public function findPropertyOfId(int $id): Property;

    /**
     * @param string $slug
     * @param string $city
     * @return Property
     */
    public function findPropertyOfSlug(string $slug, string $city): Property;

    /**
     * @param int $limit
     * @return Property[]
     */
    public function findLatest(int $limit = 3): array;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Property|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null);

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Property[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return array{items: Property[], total: int, current_page: int, per_page: int, last_page: int}
     */
    public function search(array $filters, int $page = 1, int $perPage = 10): array;

    public function save(Property $property): void;

    public function delete(Property $property): void;
}
