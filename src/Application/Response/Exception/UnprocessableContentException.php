<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use App\Application\Actions\ActionError;

class UnprocessableContentException extends RouterException
{
    public function getStatusCode(): int
    {
        return 422;
    }
    public function getErrorType(): string
    {
        return ActionError::VALIDATION_ERROR;
    }
}
