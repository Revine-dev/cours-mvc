<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use App\Application\Actions\ActionError;

class ExpiredPageException extends RouterException
{
    public function getStatusCode(): int
    {
        return 419;
    }
    public function getErrorType(): string
    {
        return ActionError::EXPIRED;
    }
}
