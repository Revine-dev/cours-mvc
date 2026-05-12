<?php

declare(strict_types=1);

namespace Tests\Application;

use Tests\TestCase;

class HomeActionTest extends TestCase
{
    public function testHomeActionError()
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
