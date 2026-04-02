<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Response\Exception\ExpiredPageException;
use App\Application\Response\Exception\UnauthorizedException;
use App\Application\Response\Response;
use App\Entity\User\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Exception\HttpUnauthorizedException;

class IsAdminMiddleware implements MiddlewareInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, Handler $handler): ResponseInterface
    {
        $session = $request->getAttribute('session') ?? $_SESSION ?? [];
        $userId = $session['user_id'] ?? null;

        if (!$userId) {
            throw new HttpUnauthorizedException($request, "Vous devez être connecté pour accéder à cette page.");
        }

        // Extra security: Validate IP and User Agent if they were stored
        $storedIp = $session['user_ip'] ?? null;
        $storedUa = $session['user_agent'] ?? null;
        $currentIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $currentUa = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        if (($storedIp && $storedIp !== $currentIp) || ($storedUa && $storedUa !== $currentUa)) {
            // Potential session hijacking, destroy session and force login
            session_destroy();
            throw new ExpiredPageException("Session invalide ou expirée (sécurité).");
        }

        $user = $this->userRepository->findOneBy('id', (int) $userId);
        if (!$user || !$user->isAdmin()) {
            throw new UnauthorizedException("Accès réservé aux administrateurs");
        }
        return $handler->handle($request);
    }
}
