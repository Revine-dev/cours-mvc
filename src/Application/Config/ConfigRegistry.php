<?php

declare(strict_types=1);

namespace App\Application\Config;

class ConfigRegistry implements ConfigRegistryInterface
{
    /**
     * @var array
     */
    private static array $configs = [];

    /**
     * Enregistre automatiquement plusieurs configurations dans le registre.
     *
     * Parcourt le tableau `$configs` et ajoute chaque clé/valeur
     * en utilisant la méthode `set`.
     *
     * @param array $configs Tableau associatif contenant les configurations à enregistrer
     * @return void
     */
    public static function register(array $configs): void
    {
        foreach ($configs as $key => $value) {
            self::set($key, $value);
        }
    }

    /**
     * Définit une configuration dans le registre.
     *
     * Ajoute ou met à jour la valeur associée à la clé `$key`.
     *
     * @param string $key   Clé de la configuration
     * @param mixed  $value Valeur à enregistrer
     * @return void
     */
    public static function set(string $key, $value): void
    {
        self::$configs[$key] = $value;
    }

    /**
     * Récupère la valeur d'une configuration dans le registre.
     *
     * Si la clé `$key` n'existe pas, retourne la valeur `$default`.
     *
     * @param string       $key     Clé de la configuration à récupérer
     * @param mixed|null   $default Valeur retournée si la clé n'existe pas
     * @return mixed               Valeur de la configuration ou `$default`
     */
    public static function get(string $key, $default = null)
    {
        return self::$configs[$key] ?? $default;
    }
}
