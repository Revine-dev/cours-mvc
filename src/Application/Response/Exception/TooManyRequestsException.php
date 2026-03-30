<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use App\Application\Actions\ActionError;

class TooManyRequestsException extends RouterException
{
    public function getStatusCode(): int
    {
        return 429;
    }
    public function getErrorType(): string
    {
        return ActionError::TOO_MANY_REQUESTS;
    }
}
