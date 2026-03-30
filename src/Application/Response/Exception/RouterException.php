<?php

declare(strict_types=1);

namespace App\Application\Response\Exception;

use RuntimeException;

abstract class RouterException extends RuntimeException
{
    abstract public function getStatusCode(): int;
    abstract public function getErrorType(): string;
}
