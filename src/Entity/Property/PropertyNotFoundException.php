<?php

declare(strict_types=1);

namespace App\Entity\Property;

use App\Entity\EntityException\EntityRecordNotFoundException;

class PropertyNotFoundException extends EntityRecordNotFoundException
{
    public $message = 'The property you requested does not exist.';
}
