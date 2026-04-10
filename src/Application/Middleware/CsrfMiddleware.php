<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Helpers\Csrf;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response as SlimResponse;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, Handler $handler): Response
    {
        // 1. Validate CSRF on POST requests
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $formId = $data['csrf_form_id'] ?? null;
            $token = $data['csrf_token'] ?? null;

            // This will throw ExpiredPageException (419) if invalid
            Csrf::validateToken((string)$formId, (string)$token);
        }

        // 2. Proceed to the next handler
        $response = $handler->handle($request);

        // 3. Automatically inject tokens into HTML responses
        $contentType = $response->getHeaderLine('Content-Type');
        if (strpos($contentType, 'text/html') !== false) {
            $body = (string) $response->getBody();
            $newBody = Csrf::autoInject($body);

            // Create a new response but preserve status and headers
            $newResponse = new SlimResponse($response->getStatusCode());
            foreach ($response->getHeaders() as $name => $values) {
                $newResponse = $newResponse->withHeader($name, $values);
            }

            $newResponse->getBody()->write($newBody);
            return $newResponse;
        }

        return $response;
    }
}
