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

    // --- Query Builder Methods ---

    public function findLatest(int $limit = 3): array;

    public function findOneBy(string $key, mixed $value): ?Property;

    public function where(string $key, mixed $value): static;

    public function whereIn(string $key, array $values): static;

    public function whereLike(string $key, string $value): static;

    public function whereGreaterThanOrEqual(string $key, mixed $value): static;

    public function whereLessThanOrEqual(string $key, mixed $value): static;

    public function filter(callable $callback): static;

    public function limit(int $n): static;

    public function latest(): static;

    /**
     * @return Property[]
     */
    public function get(): array;

    /**
     * @param int $page
     * @param int $perPage
     * @return array{items: Property[], total: int, current_page: int, per_page: int, last_page: int}
     */
    public function paginate(int $page, int $perPage = 10): array;

    public function save(Property $property): void;

    public function delete(Property $property): void;
}
