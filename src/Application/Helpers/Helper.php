<?php

declare(strict_types=1);

namespace App\Application\Helpers;

use Psr\Container\ContainerInterface;
use BadMethodCallException;

class Helper
{
    private static ?ContainerInterface $containerInstance = null;
    private ContainerInterface $container;
    
    /**
     * Liste des classes de helpers à scanner pour les méthodes.
     */
    private array $helpers = [
        StringHelper::class,
        RouterHelper::class,
        ConfigHelper::class,
    ];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        self::$containerInstance = $container;
    }

    public static function setContainer(ContainerInterface $container): void
    {
        self::$containerInstance = $container;
    }

    public static function getContainer(): ?ContainerInterface
    {
        return self::$containerInstance;
    }

    /**
     * Délégation dynamique des appels de méthodes.
     */
    public function __call(string $name, array $arguments)
    {
        foreach ($this->helpers as $helperClass) {
            $instance = $this->container->get($helperClass);
            if (method_exists($instance, $name)) {
                return $instance->$name(...$arguments);
            }
        }

        throw new BadMethodCallException("La méthode helper '{$name}' n'existe dans aucun service enregistré.");
    }

    /**
     * Permet à is_callable de fonctionner pour les méthodes magiques.
     */
    public function __isset(string $name): bool
    {
        foreach ($this->helpers as $helperClass) {
            $instance = $this->container->get($helperClass);
            if (method_exists($instance, $name)) {
                return true;
            }
        }
        return false;
    }
}
