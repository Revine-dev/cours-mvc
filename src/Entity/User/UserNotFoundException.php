<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\EntityException\EntityRecordNotFoundException;

class UserNotFoundException extends EntityRecordNotFoundException
{
    public $message = 'The user you requested does not exist.';
}
