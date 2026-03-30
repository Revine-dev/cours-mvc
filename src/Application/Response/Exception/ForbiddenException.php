<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use App\Application\Actions\ActionError;

class ForbiddenException extends RouterException
{
    public function getStatusCode(): int
    {
        return 403;
    }
    public function getErrorType(): string
    {
        return ActionError::INSUFFICIENT_PRIVILEGES;
    }
}
