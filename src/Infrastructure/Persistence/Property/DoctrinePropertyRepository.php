<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Property;

use App\Entity\Property\Property;
use App\Entity\Property\PropertyRepository;
use App\Entity\Property\PropertyNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrinePropertyRepository extends EntityRepository implements PropertyRepository
{
    private function getBaseQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
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

    public function findPropertyOfSlug(string $slug, string $city): Property
    {
        $property = $this->getBaseQueryBuilder()
            ->andWhere('p.slug = :slug')
            ->andWhere('l.city = :city')
            ->setParameter('slug', $slug)
            ->setParameter('city', $city)
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
            ->andWhere('p.status IN (:statuses)')
            ->setParameter('statuses', ['for_sale', 'compromise'])
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery();
        return iterator_to_array(new Paginator($query, true));
    }

    public function search(array $filters, int $page = 1, int $perPage = 10): array
    {
        $qb = $this->getBaseQueryBuilder();

        if (!empty($filters['status_in'])) {
            $qb->andWhere('p.status IN (:statuses)')
                ->setParameter('statuses', $filters['status_in']);
        }

        if (!empty($filters['type'])) {
            $qb->andWhere('p.type = :type')
                ->setParameter('type', $filters['type']);
        }

        if (!empty($filters['city'])) {
            $qb->andWhere('l.city LIKE :city')
                ->setParameter('city', '%' . $filters['city'] . '%');
        }

        if (!empty($filters['min_price'])) {
            $qb->andWhere('p.price >= :min_price')
                ->setParameter('min_price', (int)$filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $qb->andWhere('p.price <= :max_price')
                ->setParameter('max_price', (int)$filters['max_price']);
        }

        $qb->orderBy('p.id', 'DESC');

        $qb->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $query = $qb->getQuery();
        $paginator = new Paginator($query, true);

        $total = count($paginator);
        $items = iterator_to_array($paginator->getIterator());

        return [
            'items' => $items,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'last_page' => (int)ceil($total / $perPage)
        ];
    }

    public function save(Property $property): void
    {
        $this->getEntityManager()->persist($property);
        $this->getEntityManager()->flush();
    }

    public function delete(Property $property): void
    {
        $this->getEntityManager()->remove($property);
        $this->getEntityManager()->flush();
    }
}
