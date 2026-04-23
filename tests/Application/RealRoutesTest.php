<?php

declare(strict_types=1);

namespace Tests\Application;

use Tests\TestCase;

class RealRoutesTest extends TestCase
{
    public function testHomePage()
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('text/html', $response->getHeaderLine('Content-Type'));
    }

    public function testLoginPage()
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/back-office');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Connexion', (string)$response->getBody());
    }

    public function testPropertiesPage()
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/vente');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEstimatePage()
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/estimer-mon-bien');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPropertyDetailPage()
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/vente/paris/test-property');
        $response = $app->handle($request);

        // It should return 404 because the property doesn't exist in the DB
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testAdminReturnsUnauthorizedWhenNotAuthenticated()
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/admin');
        $response = $app->handle($request);

        $this->assertEquals(401, $response->getStatusCode());
    }
}
