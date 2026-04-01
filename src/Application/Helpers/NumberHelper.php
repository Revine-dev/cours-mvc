<?php

declare(strict_types=1);

namespace App\Application\Helpers;

class NumberHelper
{
    public function format_price(int|float $value, string $currency = '€'): string
    {
        return number_format((float)$value, 0, ',', ' ') . ' ' . $currency;
    }

    public function format_number(int|float $value, int $decimals = 0): string
    {
        return number_format((float)$value, $decimals, ',', ' ');
    }
}
