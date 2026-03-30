<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use App\Application\Actions\ActionError;

class UnauthorizedException extends RouterException
{
    public function getStatusCode(): int
    {
        return 401;
    }
    public function getErrorType(): string
    {
        return ActionError::UNAUTHENTICATED;
    }
}
