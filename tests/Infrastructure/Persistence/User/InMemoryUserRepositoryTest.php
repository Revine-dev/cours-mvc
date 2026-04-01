<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use Tests\TestCase;

class InMemoryUserRepositoryTest extends TestCase
{
    public function testFindAll()
    {
        $user = new User(['id' => 1, 'name' => 'Bill Gates', 'email' => 'bill.gates@microsoft.com']);

        $userRepository = new InMemoryUserRepository([1 => $user]);

        $this->assertEquals([$user], $userRepository->findAll());
    }

    public function testFindAllUsersByDefault()
    {
        $users = [
            1 => new User(['id' => 1, 'name' => 'Bill Gates', 'email' => 'bill.gates@microsoft.com']),
            2 => new User(['id' => 2, 'name' => 'Steve Jobs', 'email' => 'steve.jobs@apple.com']),
            3 => new User(['id' => 3, 'name' => 'Mark Zuckerberg', 'email' => 'mark.zuckerberg@facebook.com']),
            4 => new User(['id' => 4, 'name' => 'Evan Spiegel', 'email' => 'evan.spiegel@snapchat.com']),
            5 => new User(['id' => 5, 'name' => 'Jack Dorsey', 'email' => 'jack.dorsey@twitter.com']),
        ];

        $userRepository = new InMemoryUserRepository();

        $this->assertEquals(array_values($users), $userRepository->findAll());
    }

    public function testFindUserOfId()
    {
        $user = new User(['id' => 1, 'name' => 'Bill Gates', 'email' => 'bill.gates@microsoft.com']);

        $userRepository = new InMemoryUserRepository([1 => $user]);

        $this->assertEquals($user, $userRepository->findUserOfId(1));
    }

    public function testFindUserOfIdThrowsNotFoundException()
    {
        $userRepository = new InMemoryUserRepository([]);
        $this->expectException(UserNotFoundException::class);
        $userRepository->findUserOfId(1);
    }

    public function testFindOneBy()
    {
        $userRepository = new InMemoryUserRepository();
        $user = $userRepository->findOneBy('name', 'Bill Gates');
        $this->assertEquals('Bill Gates', $user->name);
    }

    public function testWhereGet()
    {
        $userRepository = new InMemoryUserRepository();
        $users = $userRepository->where('name', 'Steve Jobs')->get();
        $this->assertCount(1, $users);
        $this->assertEquals('Steve Jobs', $users[0]->name);
    }
}
