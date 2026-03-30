<?php

declare(strict_types=1);

namespace App\Application\Response;

use Psr\Http\Message\ResponseFactoryInterface;

class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): Response
    {
        return (new Response())->withStatus($code, $reasonPhrase);
    }
}
