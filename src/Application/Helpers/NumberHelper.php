<?php

declare(strict_types=1);

namespace App\Application\Helpers;

class NumberHelper
{
    public function formatPrice(int|float $value, string $currency = '€'): string
    {
        return number_format((float)$value, 0, ',', ' ') . ' ' . $currency;
    }

    /**
     * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function format_price(int|float $value, string $currency = '€'): string
    {
        return $this->formatPrice($value, $currency);
    }
    // phpcs:enable

    public function formatNumber(int|float $value, int $decimals = 0): string
    {
        return number_format((float)$value, $decimals, ',', ' ');
    }

    /**
     * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function format_number(int|float $value, int $decimals = 0): string
    {
        return $this->formatNumber($value, $decimals);
    }
    // phpcs:enable

    public function toInt(mixed $value): int
    {
        return (int)$value;
    }

    /**
     * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function to_int(mixed $value): int
    {
        return $this->toInt($value);
    }
    // phpcs:enable
}
