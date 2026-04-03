<?php

declare(strict_types=1);

namespace App\Entity\Agent;

use App\Entity\EntityException\EntityRecordNotFoundException;

class AgentNotFoundException extends EntityRecordNotFoundException
{
    public $message = 'The agent you requested does not exist.';
}
