<?php

declare(strict_types=1);

namespace Tests\Application\Actions;

use App\Application\Actions\Action;
use App\Application\Actions\ActionPayload;
use App\Application\Response\Response;
use App\Application\Helpers\Helper;
use App\Entity\EntityException\EntityRecordNotFoundException;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class ActionTest extends TestCase
{
    public function testActionSetsHttpCodeInRespond()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);
        $helper = $container->get(Helper::class);

        $testAction = new class ($logger, $helper) extends Action {
            public function action(): Response
            {
                return $this->respond(new ActionPayload(202, ['willBeDoneAt' => (new DateTimeImmutable())->format(DateTimeImmutable::ATOM)]));
            }
        };

        $app->get('/test-action-response-code', $testAction);
        $request = $this->createRequest('GET', '/test-action-response-code');
        $response = $app->handle($request);
        $this->assertEquals(202, $response->getStatusCode());
    }

    public function testActionSetsHttpCodeRespondData()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);
        $helper = $container->get(Helper::class);

        $testAction = new class ($logger, $helper) extends Action {
            public function action(): Response
            {
                return $this->respondWithData(['willBeDoneAt' => (new DateTimeImmutable())->format(DateTimeImmutable::ATOM)], 202);
            }
        };

        $app->get('/test-action-response-code', $testAction);
        $request = $this->createRequest('GET', '/test-action-response-code');
        $response = $app->handle($request);
        $this->assertEquals(202, $response->getStatusCode());
    }

    public function testActionRespondWithNotFound()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);
        $helper = $container->get(Helper::class);

        $testAction = new class ($logger, $helper) extends Action {
            public function action(): Response
            {
                $this->response->notFound('Resource not found.');
            }
        };

        $app->get('/test-action-not-found', $testAction);
        $request = $this->createRequest('GET', '/test-action-not-found');
        $response = $app->handle($request);
        $this->assertEquals(404, $response->getStatusCode());
        $body = (string) $response->getBody();
        $this->assertStringContainsString('RESOURCE_NOT_FOUND', $body);
        $this->assertStringContainsString('Resource not found', $body);
    }

    public function testActionGlobalNotFound()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);
        $helper = $container->get(Helper::class);

        $testAction = new class ($logger, $helper) extends Action {
            public function action(): Response
            {
                $this->response->notFound('Action not found.');
            }
        };

        $app->get('/test-global-not-found', $testAction);
        $request = $this->createRequest('GET', '/test-global-not-found');
        $response = $app->handle($request);
        $this->assertEquals(404, $response->getStatusCode());
        $body = (string) $response->getBody();
        $this->assertStringContainsString('RESOURCE_NOT_FOUND', $body);
        $this->assertStringContainsString('Action not found', $body);
    }

    public function testActionHtmlNotFound()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);
        $helper = $container->get(Helper::class);

        $testAction = new class ($logger, $helper) extends Action {
            public function action(): Response
            {
                $msg = 'Page introuvable';
                return $this->render("<h1>404</h1><p>{$msg}</p>");
            }
        };

        $app->get('/test-html-not-found', $testAction);
        $request = $this->createRequest('GET', '/test-html-not-found', ['Accept' => 'text/html']);
        $response = $app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $body = (string) $response->getBody();
        $this->assertStringContainsString('404', $body);
        $this->assertStringContainsString('Page introuvable', $body);
    }

    public function testActionForbidden()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);
        $helper = $container->get(Helper::class);

        $testAction = new class ($logger, $helper) extends Action {
            public function action(): Response
            {
                $this->response = $this->response->withStatus(403);
                return $this->render('<h1>403 Forbidden</h1>');
            }
        };

        $app->get('/test-forbidden', $testAction);
        $request = $this->createRequest('GET', '/test-forbidden', ['Accept' => 'text/html']);
        $response = $app->handle($request);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('403', (string) $response->getBody());
    }

    public function testViewNotFound()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);
        $helper = $container->get(Helper::class);

        $testAction = new class ($logger, $helper) extends Action {
            public function action(): Response
            {
                return $this->render('non-existent-view');
            }
        };

        $app->get('/test-view-not-found', $testAction);
        $request = $this->createRequest('GET', '/test-view-not-found', ['Accept' => 'text/html']);
        $response = $app->handle($request);
        $this->assertEquals(500, $response->getStatusCode());
        $body = (string) $response->getBody();
        $this->assertStringContainsString('500', $body);
        $this->assertStringContainsString('View template `non-existent-view` not found', $body);
    }

    public function testEntityNotFoundHtml()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);
        $helper = $container->get(Helper::class);

        $testAction = new class ($logger, $helper) extends Action {
            public function action(): Response
            {
                throw new class('Specific entity item not found') extends EntityRecordNotFoundException {};
            }
        };

        $app->get('/test-entity-not-found', $testAction);
        $request = $this->createRequest('GET', '/test-entity-not-found', ['Accept' => 'text/html']);
        $response = $app->handle($request);
        
        $this->assertEquals(404, $response->getStatusCode());
        $body = (string) $response->getBody();
        $this->assertStringContainsString('404', $body);
        $this->assertStringContainsString('Specific entity item not found', $body);
    }
}
