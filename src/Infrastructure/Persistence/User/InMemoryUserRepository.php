<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private array $users;

    /**
     * @var User[]
     */
    private array $queryUsers;

    /**
     * @param User[]|null $users
     */
    public function __construct(?array $users = null)
    {
        $this->users = $users ?? [
            1 => new User(['id' => 1, 'name' => 'Bill Gates', 'email' => 'bill.gates@microsoft.com']),
            2 => new User(['id' => 2, 'name' => 'Steve Jobs', 'email' => 'steve.jobs@apple.com']),
            3 => new User(['id' => 3, 'name' => 'Mark Zuckerberg', 'email' => 'mark.zuckerberg@facebook.com']),
            4 => new User(['id' => 4, 'name' => 'Evan Spiegel', 'email' => 'evan.spiegel@snapchat.com']),
            5 => new User(['id' => 5, 'name' => 'Jack Dorsey', 'email' => 'jack.dorsey@twitter.com']),
        ];
        $this->queryUsers = $this->users;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(string $key, mixed $value): ?User
    {
        foreach ($this->users as $user) {
            if (isset($user->$key) && $user->$key === $value) {
                return $user;
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function where(string $key, mixed $value): static
    {
        $this->queryUsers = array_filter($this->queryUsers, function ($user) use ($key, $value) {
            return isset($user->$key) && $user->$key === $value;
        });
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        $results = array_values($this->queryUsers);
        $this->queryUsers = $this->users; // Reset
        return $results;
    }
}
