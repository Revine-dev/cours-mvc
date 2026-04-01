<?php

declare(strict_types=1);

namespace App\Application\Helpers;

class ArrayHelper
{
    public function slice(array $value, int $offset, ?int $length = null): array
    {
        return array_slice($value, $offset, $length);
    }
}
