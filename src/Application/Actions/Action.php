<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Entity\EntityException\EntityRecordNotFoundException;
use App\Application\Response\Response;
use App\Application\Helpers\Helper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

abstract class Action
{
    protected LoggerInterface $logger;

    protected Helper $helper;

    protected Request $request;

    protected Response $response;

    protected array $args;

    public function __construct(LoggerInterface $logger, Helper $helper)
    {
        $this->logger = $logger;
        $this->helper = $helper;
    }

    public function __call(string $name, array $args): mixed
    {
        if (isset($this->helper) && (method_exists($this->helper, $name) || isset($this->helper->$name))) {
            return $this->helper->$name(...$args);
        }
        throw new \BadMethodCallException("Method {$name} does not exist or Helper not initialized.");
    }

    /**
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);

        try {
            return $this->action();
        } catch (EntityRecordNotFoundException $e) {
            $this->response->notFound($e->getMessage());
        }
    }

    protected function init(Request $request, Response $response, array $args): void
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        // Pass helper to response for view rendering if initialized
        if (isset($this->helper)) {
            $this->response->setHelper($this->helper);
        }
    }

    /**
     * @throws EntityRecordNotFoundException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array|object
     */
    protected function getFormData()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param array|object|null $data
     */
    protected function respondWithData($data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    protected function redirect(string $page): Response
    {
        return $this->response->withHeader('Location', $this->route($page))->withStatus(302);
    }

    protected function render(string $view, array $data = []): Response
    {
        return $this->response->renderHtml($view, $data);
    }

    protected function respondWithError(ActionError $error, int $statusCode = 400): Response
    {
        $payload = new ActionPayload($statusCode, null, $error);

        return $this->respond($payload);
    }

    protected function respond(ActionPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode());
    }
}
