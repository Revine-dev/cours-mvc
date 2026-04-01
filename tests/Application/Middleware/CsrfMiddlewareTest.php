<?php

declare(strict_types=1);

namespace Tests\Application\Middleware;

use App\Application\Helpers\Csrf;
use Tests\TestCase;

class CsrfMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';
    }

    /**
     * @runInSeparateProcess
     */
    public function testAutoInjectInMiddleware()
    {
        $app = $this->getAppInstance();
        
        $app->get('/test-form', function ($request, $response) {
            $response->getBody()->write('<html><body><form method="POST" action="/submit"></form></body></html>');
            return $response->withHeader('Content-Type', 'text/html');
        });

        $request = $this->createRequest('GET', '/test-form', ['Accept' => 'text/html']);
        $response = $app->handle($request);

        $body = (string) $response->getBody();
        $this->assertStringContainsString('name="csrf_form_id"', $body);
        $this->assertStringContainsString('name="csrf_token"', $body);
        $this->assertStringContainsString('value="/submit"', $body);
    }

    /**
     * @runInSeparateProcess
     */
    public function testPostValidationSuccess()
    {
        $app = $this->getAppInstance();
        
        // Pre-generate token in session
        $formId = '/submit';
        $token = Csrf::generateToken($formId);

        $app->post('/submit', function ($request, $response) {
            $response->getBody()->write('Success');
            return $response;
        });

        $request = $this->createRequest('POST', '/submit')
            ->withParsedBody([
                'csrf_form_id' => $formId,
                'csrf_token' => $token
            ]);

        $response = $app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Success', (string) $response->getBody());
    }

    /**
     * @runInSeparateProcess
     */
    public function testPostValidationFailure()
    {
        $app = $this->getAppInstance();

        $app->post('/submit', function ($request, $response) {
            return $response;
        });

        $request = $this->createRequest('POST', '/submit')
            ->withParsedBody([
                'csrf_form_id' => '/submit',
                'csrf_token' => 'invalid_token'
            ]);

        // Should return 419 due to ExpiredPageException handled by HttpErrorHandler
        $response = $app->handle($request);
        $this->assertEquals(419, $response->getStatusCode());
    }
}
