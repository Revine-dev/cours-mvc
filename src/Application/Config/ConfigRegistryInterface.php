<?php

declare(strict_types=1);

namespace App\Application\Config;

interface ConfigRegistryInterface
{
    /**
     * Enregistre automatiquement plusieurs configurations dans le registre.
     *
     * @param array $configs
     * @return void
     */
    public static function register(array $configs): void;

    /**
     * Définit une configuration dans le registre.
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public static function set(string $key, $value): void;

    /**
     * Récupère une configuration.
     *
     * @param string     $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(string $key, $default = null);
}
