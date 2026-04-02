<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Property;

use App\Entity\Property\Property;
use App\Entity\Property\PropertyRepository;
use App\Entity\Property\PropertyNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrinePropertyRepository implements PropertyRepository
{
    private EntityRepository $repository;
    private ?QueryBuilder $qb = null;

    public function __construct(private EntityManager $em)
    {
        $this->repository = $em->getRepository(Property::class);
    }

    private function getBaseQueryBuilder(): QueryBuilder
    {
        return $this->repository->createQueryBuilder('p')
            ->select('p', 'l', 'a', 'i', 'am')
            ->leftJoin('p.location', 'l')
            ->leftJoin('p.agent', 'a')
            ->leftJoin('p.propertyImages', 'i')
            ->leftJoin('p.amenities', 'am');
    }

    public function findAll(): array
    {
        return $this->getBaseQueryBuilder()->getQuery()->getResult();
    }

    public function findPropertyOfId(int $id): Property
    {
        $property = $this->getBaseQueryBuilder()
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$property) {
            throw new PropertyNotFoundException();
        }
        return $property;
    }

    public function findPropertyOfSlug(string $slug): Property
    {
        $property = $this->getBaseQueryBuilder()
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$property) {
            throw new PropertyNotFoundException();
        }
        return $property;
    }

    public function findLatest(int $limit = 3): array
    {
        $query = $this->getBaseQueryBuilder()
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery();
        return iterator_to_array(new Paginator($query, true));
    }

    public function findOneBy(string $key, mixed $value): ?Property
    {
        return $this->getBaseQueryBuilder()
            ->andWhere("p.$key = :val")
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function where(string $key, mixed $value): static
    {
        if ($this->qb === null) {
            $this->qb = $this->getBaseQueryBuilder();
        }
        $this->qb->andWhere("p.$key = :$key")
            ->setParameter($key, $value);
        return $this;
    }

    public function whereLike(string $key, string $value): static
    {
        if ($this->qb === null) {
            $this->qb = $this->getBaseQueryBuilder();
        }
        $paramName = str_replace('.', '_', $key) . '_like';
        // Use p. for standard columns, or leave it if already prefixed (e.g., l.city)
        $field = strpos($key, '.') !== false ? $key : "p.$key";
        $this->qb->andWhere("$field LIKE :$paramName")
            ->setParameter($paramName, '%' . $value . '%');
        return $this;
    }

    public function whereGreaterThanOrEqual(string $key, mixed $value): static
    {
        if ($this->qb === null) {
            $this->qb = $this->getBaseQueryBuilder();
        }
        $paramName = str_replace('.', '_', $key) . '_gte';
        $field = strpos($key, '.') !== false ? $key : "p.$key";
        $this->qb->andWhere("$field >= :$paramName")
            ->setParameter($paramName, $value);
        return $this;
    }

    public function whereLessThanOrEqual(string $key, mixed $value): static
    {
        if ($this->qb === null) {
            $this->qb = $this->getBaseQueryBuilder();
        }
        $paramName = str_replace('.', '_', $key) . '_lte';
        $field = strpos($key, '.') !== false ? $key : "p.$key";
        $this->qb->andWhere("$field <= :$paramName")
            ->setParameter($paramName, $value);
        return $this;
    }

    public function filter(callable $callback): static
    {
        return $this;
    }

    public function limit(int $n): static
    {
        if ($this->qb === null) {
            $this->qb = $this->getBaseQueryBuilder();
        }
        $this->qb->setMaxResults($n);
        return $this;
    }

    public function latest(): static
    {
        if ($this->qb === null) {
            $this->qb = $this->getBaseQueryBuilder();
        }
        $this->qb->orderBy('p.id', 'DESC');
        return $this;
    }

    public function get(): array
    {
        $query = $this->qb ? $this->qb->getQuery() : $this->getBaseQueryBuilder()->getQuery();

        // Use Paginator to handle joins with setMaxResults correctly
        $paginator = new Paginator($query, true);
        $results = iterator_to_array($paginator->getIterator());

        $this->qb = null;
        return $results;
    }
}
