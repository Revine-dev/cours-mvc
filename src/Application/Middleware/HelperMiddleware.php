<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Helpers\RouterHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class HelperMiddleware implements MiddlewareInterface
{
    public function process(Request $request, Handler $handler): Response
    {
        // On capture la requête pour le helper
        RouterHelper::setRequest($request);
        
        return $handler->handle($request);
    }
}
