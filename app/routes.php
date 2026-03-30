<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Response\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/test', function (Request $request, Response $response) {
        $response->renderHtml('<h1>Raw HTML</h1><p><?= $helper->slugify("Hello World") ?></p>');
        return $response;
    });

    $app->get('/favicon.ico', function (Request $request, Response $response) {
        $file = ROOT . DS . 'public' . DS . 'favicon.png';
        if (file_exists($file)) {
            $response->getBody()->write(file_get_contents($file));
            return $response->withHeader('Content-Type', 'image/png');
        }
        return $response->withStatus(404);
    });

    $app->get('/admin', function (Request $request, Response $response) {
        return $response->unauthorized();
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
