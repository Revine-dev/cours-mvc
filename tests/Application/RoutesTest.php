<?php

declare(strict_types=1);

namespace Tests\Application;

use App\Application\Response\Response;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    public function testRouteHasCustomResponse()
    {
        $app = $this->getAppInstance();

        $app->get('/test-custom-response', function ($request, $response) {
            $response->notFound('Route level not found');
        });

        $request = $this->createRequest('GET', '/test-custom-response');
        $response = $app->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $body = (string) $response->getBody();
        $this->assertStringContainsString('RESOURCE_NOT_FOUND', $body);
        $this->assertStringContainsString('Route level not found', $body);
    }
}
