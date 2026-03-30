<?php

declare(strict_types=1);

namespace Tests\Application\Response;

use App\Application\Response\Response;
use App\Application\Helpers\RouterHelper;
use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testRenderHtml()
    {
        $this->getAppInstance(); // Crucial to set Helper::$containerInstance
        $response = new Response();
        $response = $response->renderHtml('error', [
            'code' => 404,
            'title' => '<b>Not Found</b>',
            'message' => 'test message'
        ]);

        $body = (string) $response->getBody();
        $this->assertStringContainsString('&lt;b&gt;Not Found&lt;/b&gt;', $body);
        $this->assertStringContainsString('test message', $body);
        $this->assertEquals('text/html', $response->getHeaderLine('Content-Type'));
    }

    public function testHelpersInView()
    {
        $this->getAppInstance();
        $request = (new ServerRequestFactory())->createServerRequest('GET', 'http://example.com/test?foo=bar');
        RouterHelper::setRequest($request);

        $response = new Response();
        $html = 'URL:<?= $this->current_url() ?>|Config:<?= $this->config("db_host") ?>';
        $response = $response->renderHtml($html);

        $body = (string) $response->getBody();
        $this->assertStringContainsString('URL:http://example.com/test?foo=bar', $body);
        $this->assertStringContainsString('Config:localhost', $body);
    }

    public function testRenderHtmlRaw()
    {
        $this->getAppInstance();
        $response = new Response();
        $html = '<h1>Raw HTML</h1><p><?= $this->slugify("Hello World") ?></p>';
        $response = $response->renderHtml($html);

        $body = (string) $response->getBody();
        $this->assertStringContainsString('<h1>Raw HTML</h1>', $body);
        $this->assertStringContainsString('<p>hello-world</p>', $body);
    }

    public function testNonExistentMethodThrowsException()
    {
        $this->expectException(\BadMethodCallException::class);
        $response = new Response();
        $response->methodNotFoundSoThrow();
    }
}
