<?php

declare(strict_types=1);

namespace App\Application\Helpers;

use App\Application\Config\ConfigRegistry;

class ConfigHelper
{
    /**
     * Retourne une valeur de configuration.
     */
    public function config(string $key, mixed $default = null): mixed
    {
        return ConfigRegistry::get($key) ?? $default;
    }
}
