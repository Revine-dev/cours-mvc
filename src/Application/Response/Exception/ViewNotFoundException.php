<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use App\Application\Actions\ActionError;

class ViewNotFoundException extends AppException
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
