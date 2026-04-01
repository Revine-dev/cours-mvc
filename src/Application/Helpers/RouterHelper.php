<?php

declare(strict_types=1);

namespace App\Application\Helpers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Slim\Routing\RouteContext;
use UnexpectedValueException;

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
        try {
            return RouteContext::fromRequest(self::$currentRequest)->getRouteParser();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getCurrentRoute(): string
    {
        if (!self::$currentRequest) return '';
        $routeContext = RouteContext::fromRequest(self::$currentRequest);
        $route = $routeContext->getRoute();
        return $route ? (string) $route->getName() : "";
    }

    /**
     * Retourne l'URL courante.
     */
    public function current_url(string $get = ""): string
    {
        if (!self::$currentRequest) return '';

        $route = null;
        try {
            $routeContext = RouteContext::fromRequest(self::$currentRequest);
            $route = $routeContext->getRoute();
        } catch (\Throwable $e) {
            // Ignore if routing not done
        }

        $uri = self::$currentRequest->getUri();
        if (!$get && $route) {
            return (string) $route->getName();
        } else if (!$get && !$route) {
            return (string) $uri->getPath();
        }

        $url = $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath();
        if ($get === "FULL" && $query = $uri->getQuery()) {
            $url .= '?' . $query;
        }
        return $url;
    }

    public function isActiveRoute(string $routeName): bool
    {
        if (!self::$currentRequest) return false;
        try {
            $routeContext = RouteContext::fromRequest(self::$currentRequest);
            $route = $routeContext->getRoute();
            return $route ? (string) $route->getName() === $routeName : false;
        } catch (\Throwable $e) {
            return false;
        }
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

        try {
            $route = RouteContext::fromRequest(self::$currentRequest)->getRoute();
            if (!$route) return false;

            if ($route->getName() !== $name) return false;

            foreach ($data as $key => $value) {
                if ($route->getArgument($key) !== (string)$value) return false;
            }

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
