<?php

declare(strict_types=1);

namespace App\Application\Helpers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Slim\Routing\RouteContext;

class RouterHelper
{
    private static ?Request $currentRequest = null;

    public static function setRequest(Request $request): void
    {
        self::$currentRequest = $request;
    }

    /**
     * Récupère le parser de routes depuis la requête courante.
     */
    private function getParser(): ?RouteParserInterface
    {
        if (!self::$currentRequest) return null;
        return RouteContext::fromRequest(self::$currentRequest)->getRouteParser();
    }

    /**
     * Retourne l'URL courante.
     */
    public function current_url(bool $withQuery = true): string
    {
        if (!self::$currentRequest) return '';
        $uri = self::$currentRequest->getUri();
        $url = $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath();
        if ($withQuery && $query = $uri->getQuery()) {
            $url .= '?' . $query;
        }
        return $url;
    }

    /**
     * Génère l'URL pour une route nommée.
     */
    public function route(string $name, array $data = [], array $queryParams = []): string
    {
        $parser = $this->getParser();
        return $parser ? $parser->urlFor($name, $data, $queryParams) : '';
    }

    /**
     * Vérifie si une route est active.
     */
    public function is_route_active(string $name, array $data = []): bool
    {
        if (!self::$currentRequest) return false;
        
        $route = RouteContext::fromRequest(self::$currentRequest)->getRoute();
        if (!$route) return false;

        if ($route->getName() !== $name) return false;

        foreach ($data as $key => $value) {
            if ($route->getArgument($key) !== (string)$value) return false;
        }

        return true;
    }
}
