<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use App\Application\Actions\ActionError;

class InternalErrorException extends RouterException
{
    public function getStatusCode(): int
    {
        return 500;
    }
    public function getErrorType(): string
    {
        return ActionError::SERVER_ERROR;
    }
}
