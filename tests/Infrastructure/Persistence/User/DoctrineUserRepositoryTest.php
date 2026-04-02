<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Entity\User\User;
use App\Infrastructure\Persistence\User\DoctrineUserRepository;
use Tests\DatabaseTestCase;

class DoctrineUserRepositoryTest extends DatabaseTestCase
{
    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DoctrineUserRepository($this->getEntityManager());
    }

    public function testFindAll(): void
    {
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'role' => 'User'
        ]);

        $this->em->persist($user);
        $this->em->flush();

        $users = $this->repository->findAll();
        $this->assertCount(1, $users);
        $this->assertEquals('John Doe', $users[0]->name);
    }

    public function testFindUserOfId(): void
    {
        $user = new User([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'role' => 'User'
        ]);

        $this->em->persist($user);
        $this->em->flush();

        $foundUser = $this->repository->findUserOfId($user->id);
        $this->assertEquals('Jane Doe', $foundUser->name);
    }

    public function testFindOneBy(): void
    {
        $user = new User([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password',
            'role' => 'User'
        ]);

        $this->em->persist($user);
        $this->em->flush();

        $foundUser = $this->repository->findOneBy('email', 'alice@example.com');
        $this->assertNotNull($foundUser);
        $this->assertEquals('Alice', $foundUser->name);
    }

    public function testWhereAndGet(): void
    {
        $user1 = new User([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password',
            'role' => 'User'
        ]);
        $user2 = new User([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => 'password',
            'role' => 'Admin'
        ]);

        $this->em->persist($user1);
        $this->em->persist($user2);
        $this->em->flush();

        $admins = $this->repository->where('role', 'Admin')->get();
        $this->assertCount(1, $admins);
        $this->assertEquals('Bob', $admins[0]->name);
    }
}
