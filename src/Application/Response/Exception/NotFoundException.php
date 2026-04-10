<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use App\Application\Actions\ActionError;

class NotFoundException extends RouterException
{
    public function getStatusCode(): int
    {
        return 404;
    }
    public function getErrorType(): string
    {
        return ActionError::RESOURCE_NOT_FOUND;
    }
}
