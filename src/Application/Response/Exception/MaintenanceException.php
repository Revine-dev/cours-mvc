<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use App\Application\Actions\ActionError;

class MaintenanceException extends RouterException
{
    public function getStatusCode(): int
    {
        return 503;
    }
    public function getErrorType(): string
    {
        return ActionError::MAINTENANCE;
    }
}
