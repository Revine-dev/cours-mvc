<?php

declare(strict_types=1);

namespace App\Application\Response;

use App\Application\Response\Exception\ForbiddenException;
use App\Application\Response\Exception\InternalErrorException;
use App\Application\Response\Exception\MaintenanceException;
use App\Application\Response\Exception\NotFoundException;
use App\Application\Response\Exception\TooManyRequestsException;
use App\Application\Response\Exception\UnauthorizedException;
use App\Application\Response\Exception\UnprocessableContentException;
use App\Application\Response\Exception\ViewNotFoundException;
use App\Application\Response\ViewVariable;
use App\Application\Helpers\Helper;
use Slim\Psr7\Response as SlimResponse;
use Throwable;
use BadMethodCallException;

class Response extends SlimResponse
{
    private ?Helper $helper = null;

    public function setHelper(Helper $helper): void
    {
        $this->helper = $helper;
    }

    public function __call(string $name, array $args): mixed
    {
        if ($container = Helper::getContainer()) {
            // Déballage automatique des arguments si ce sont des ViewVariables
            $unwrapped = array_map(fn($arg) => ($arg instanceof ViewVariable) ? $arg->dangerousRaw() : $arg, $args);

            return new ViewVariable($container->get(Helper::class)->$name(...$unwrapped));
        }
        throw new BadMethodCallException("Method {$name} called but Helper system not initialized.");
    }

    public function notFound(?string $msg = null): never
    {
        throw new NotFoundException($msg ?? 'Not Found');
    }
    public function forbidden(?string $msg = null): never
    {
        throw new ForbiddenException($msg ?? 'Forbidden');
    }
    public function unauthorized(?string $msg = null): never
    {
        throw new UnauthorizedException($msg ?? 'Unauthorized');
    }
    public function maintenance(?string $msg = null): never
    {
        throw new MaintenanceException($msg ?? 'Service Unavailable');
    }
    public function tooManyRequests(?string $msg = null): never
    {
        throw new TooManyRequestsException($msg ?? 'Too Many Requests');
    }
    public function error(?string $msg = null): never
    {
        throw new InternalErrorException($msg ?? 'Internal Server Error');
    }
    public function unprocessableContent(?string $msg = null): never
    {
        throw new UnprocessableContentException($msg ?? 'Unprocessable Content');
    }

    public function renderHtml(string $view, array $data = []): self
    {
        $viewPath = VIEWS . $view . '.php';
        $isRaw = !file_exists($viewPath) && str_contains($view, '<') && str_contains($view, '>');

        if (!file_exists($viewPath) && !$isRaw) {
            throw new ViewNotFoundException("View template `{$view}` not found.");
        }

        $escapedData = [];
        foreach ($data as $k => $v) {
            $escapedData[$k] = (is_scalar($v) && empty($v)) || $v === null ? $v : new ViewVariable($v);
        }

        if (!$this->helper && $container = Helper::getContainer()) {
            $this->helper = $container->get(Helper::class);
        }
        if ($this->helper) {
            $escapedData['helper'] = new ViewVariable($this->helper);
            if ($user = $this->helper->auth()) {
                $escapedData['user'] = new ViewVariable($user);
            }
        }

        $tempFile = null;
        if ($isRaw) {
            $tempFile = tempnam(sys_get_temp_dir(), 'view_');
            file_put_contents($tempFile, $view);
            $viewPath = $tempFile;
        }

        ob_start();
        try {
            extract($escapedData);
            require $viewPath;
            $html = (string) ob_get_clean();
        } catch (Throwable $e) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            if ($tempFile) {
                @unlink($tempFile);
            }
            throw $e;
        }

        if ($tempFile) {
            @unlink($tempFile);
        }

        $this->getBody()->write($html);
        return $this->withHeader('Content-Type', 'text/html');
    }
}
