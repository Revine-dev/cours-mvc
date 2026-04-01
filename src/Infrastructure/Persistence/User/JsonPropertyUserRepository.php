<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\JsonRepository;

class JsonPropertyUserRepository extends JsonRepository implements UserRepository
{
    public function __construct()
    {
        parent::__construct('users', User::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(string $key, mixed $value): ?User
    {
        return parent::findOneBy($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        $user = $this->findOneBy('id', $id);
        if (!$user) throw new UserNotFoundException();
        return $user;
    }
}
