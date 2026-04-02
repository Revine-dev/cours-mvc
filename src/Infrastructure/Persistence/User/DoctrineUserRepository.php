<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Entity\User\User;
use App\Entity\User\UserRepository;
use App\Entity\User\UserNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class DoctrineUserRepository implements UserRepository
{
    private EntityRepository $repository;
    private ?QueryBuilder $qb = null;

    public function __construct(private EntityManager $em)
    {
        $this->repository = $em->getRepository(User::class);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findUserOfId(int $id): User
    {
        $user = $this->repository->find($id);
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    public function findOneBy(string $key, mixed $value): ?User
    {
        return $this->repository->findOneBy([$key => $value]);
    }

    public function where(string $key, mixed $value): static
    {
        if ($this->qb === null) {
            $this->qb = $this->repository->createQueryBuilder('u');
        }
        $this->qb->andWhere("u.$key = :$key")
            ->setParameter($key, $value);
        return $this;
    }

    public function get(): array
    {
        $results = $this->qb ? $this->qb->getQuery()->getResult() : $this->findAll();
        $this->qb = null; // Reset
        return $results;
    }
}
