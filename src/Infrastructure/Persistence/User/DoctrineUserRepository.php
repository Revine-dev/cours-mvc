<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Entity\User\User;
use App\Entity\User\UserRepository;
use App\Entity\User\UserNotFoundException;
use Doctrine\ORM\EntityRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    public function findUserOfId(int $id): User
    {
        /** @var User|null $user */
        $user = $this->find($id);
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }
}
