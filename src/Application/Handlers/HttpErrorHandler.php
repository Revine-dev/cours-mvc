<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Response\Exception\AppException;
use App\Application\Response\Exception\RouterException;
use App\Application\Response\Response;
use App\Application\Helpers\Helper;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Routing\RouteContext;
use App\Application\Config\ConfigRegistry;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Throwable;

class HttpErrorHandler extends SlimErrorHandler
{
    /**
     * @inheritdoc
     */
    public function __invoke(
        \Psr\Http\Message\ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): Response {
        $this->request = $request;
        $this->exception = $exception;
        $this->displayErrorDetails = $displayErrorDetails;
        $this->logErrors = $logErrors;
        $this->logErrorDetails = $logErrorDetails;

        // Force logging immediately before attempting to respond
        if ($this->logErrors) {
            $this->writeToErrorLog();
        }

        return $this->respond();
    }

    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = 500;
        $error = new ActionError(
            ActionError::SERVER_ERROR,
            'An internal error has occurred while processing your request'
        );

        if ($exception instanceof AppException || $exception instanceof RouterException) {
            $statusCode = $exception->getStatusCode();
            $error->setType($exception->getErrorType());
            $error->setDescription(rtrim($exception->getMessage(), '.'));
        } elseif ($exception instanceof DomainRecordNotFoundException) {
            $statusCode = 404;
            $error->setType(ActionError::RESOURCE_NOT_FOUND);
            $error->setDescription(rtrim($exception->getMessage(), '.'));
        } elseif ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $error->setDescription(rtrim($exception->getMessage(), '.'));

            $this->logError("", $exception);

            if ($exception instanceof HttpNotFoundException) {
                $error->setType(ActionError::RESOURCE_NOT_FOUND);
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $error->setType(ActionError::NOT_ALLOWED);
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $error->setType(ActionError::UNAUTHENTICATED);
            } elseif ($exception instanceof HttpForbiddenException) {
                $error->setType(ActionError::INSUFFICIENT_PRIVILEGES);
            } elseif ($exception instanceof HttpBadRequestException) {
                $error->setType(ActionError::BAD_REQUEST);
            } elseif ($exception instanceof HttpNotImplementedException) {
                $error->setType(ActionError::NOT_IMPLEMENTED);
            }
        }

        if (
            !($exception instanceof HttpException)
            && $exception instanceof Throwable
            && $this->displayErrorDetails
        ) {
            $error->setDescription(rtrim($exception->getMessage(), '.'));
        }

        $payload = new ActionPayload($statusCode, null, $error);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        /** @var Response $response */
        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write(''); // Ensure fresh body

        // Inject helper for HTML error views
        $helper = null;
        if (isset($this->container)) {
            $helper = $this->container->get(Helper::class);
            $response->setHelper($helper);
        }

        // Check if the request accepts HTML
        $contentType = $this->request->getHeaderLine('Accept');
        if (\str_contains($contentType, 'text/html')) {
            $title = match ($statusCode) {
                404 => 'This page could not be found',
                403 => 'Forbidden',
                401 => 'Unauthorized',
                429 => 'Too Many Requests',
                503 => 'Service Unavailable',
                default => 'An error occurred',
            };

            try {
                return $response->renderHtml('error', [
                    'code' => $statusCode,
                    'message' => $error->getDescription(),
                    'title' => $title,
                    'helper' => $helper
                ]);
            } catch (Throwable $e) {
                // If HTML rendering fails, log it and fall back to JSON
                error_log("CRITICAL: Error while rendering error page: " . $e->getMessage());
                $this->exception = $e;
                $this->writeToErrorLog();

                // Ensure body is clean for JSON fallback
                $response = $this->responseFactory->createResponse($statusCode);
            }
        }

        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @inheritdoc
     */
    protected function logError(string $error, ?\Exception $exception = null): void
    {
        $exception = !$error && $exception ? $exception : $this->exception;
        $request = $this->request;

        // Get the real HTTP code
        $code = $exception->getCode();
        $code = $code ?: 500;

        $method = $request->getMethod();
        $uri = (string) $request->getUri();

        // Attempt to get route pattern
        try {
            $routeContext = RouteContext::fromRequest($request);
            $route = $routeContext->getRoute();
            $routePattern = $route ? $route->getPattern() : '';
        } catch (Throwable $e) {
            $routePattern = '';
        }

        if ($routePattern) {
            $routePattern = " (Route: {$routePattern})";
        }

        if ($exception instanceof RouterException || $exception instanceof HttpException) {
            if (!ConfigRegistry::get("log_http_errors")) {
                return;
            }

            $title = match ($code) {
                404 => 'This page could not be found',
                403 => 'Forbidden',
                401 => 'Unauthorized',
                429 => 'Too Many Requests',
                503 => 'Service Unavailable',
                default => 'An error occurred',
            };

            $fullError = sprintf("[%d] %s %s%s - %s", $code, $method, $uri, $routePattern, $title);
        } else {
            // Get a clean short name for the exception class
            $reflection = new \ReflectionClass($exception);
            $exceptionName = $reflection->getShortName();

            $message = rtrim($exception->getMessage(), '.');

            // One-line clean summary
            $summary = sprintf("[%d] %s %s%s - %s (%s)", $code, $method, $uri, $routePattern, $message, $exceptionName);

            // For 4xx errors, we usually don't want the full 100-line stack trace unless debugging
            // We only show the full trace for 5xx or if logErrorDetails is true
            if ($code < 500 && !$this->logErrorDetails) {
                $fullError = sprintf("%s in %s:%d", $summary, $exception->getFile(), $exception->getLine());
            } else {
                $fullError = $summary . "\n" . $exception->getTraceAsString();
            }
        }

        // Log to Monolog (app.log)
        parent::logError($fullError);

        // Log to system (error.log)
        error_log($fullError);
    }
}
