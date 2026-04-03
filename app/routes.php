<?php

declare(strict_types=1);

use App\Application\Actions\Admin\AdminAction;
use App\Application\Actions\Home\HomeAction;
use App\Application\Actions\Property\ListPropertiesAction;
use App\Application\Actions\Property\ViewPropertyAction;
use App\Application\Actions\User\UserAction;
use App\Application\Middleware\IsAdminMiddleware;
use App\Application\Response\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/favicon.ico', function (Request $request, Response $response) {
        $file = ROOT . DS . 'public' . DS . 'favicon.png';
        if (file_exists($file)) {
            $response->getBody()->write(file_get_contents($file));
            return $response->withHeader('Content-Type', 'image/png');
        }
        return $response->withStatus(404);
    });

    $app->get('/', HomeAction::class)->setName("home");
    $app->get('/estimer-mon-bien', function (Request $request, Response $response) {
        return $response->renderHtml("estimate");
    })->setName("estimate");
    $app->get('/vente', ListPropertiesAction::class)->setName("properties");
    $app->get('/vente/{city}/{slug}', ViewPropertyAction::class)->setName("view-property");

    $app->map(['GET', 'POST'], '/back-office', UserAction::class)->setName("login");
    $app->get('/logout', UserAction::class . ':logout')->setName("logout");

    $app->group('/admin', function (Group $group) {
        $group->get('', AdminAction::class)->setName("admin");
        $group->get('/ads', AdminAction::class . ':list')->setName("ads");
        $group->get('/ads/create', AdminAction::class . ':create')->setName("create-ad");
        $group->post('/ads/create', AdminAction::class . ':store')->setName("store-ad");
        $group->get('/ads/edit/{id}', AdminAction::class . ':edit')->setName("edit-ad");
        $group->post('/ads/edit/{id}', AdminAction::class . ':update')->setName("update-ad");
        $group->post('/ads/preview[/{id}]', AdminAction::class . ':preview')->setName("preview-ad");

        $group->get('/agents', AdminAction::class . ':listAgents')->setName("agents");
        $group->get('/agents/create', AdminAction::class . ':createAgent')->setName("create-agent");
        $group->post('/agents/create', AdminAction::class . ':storeAgent')->setName("store-agent");
        $group->get('/agents/edit/{id}', AdminAction::class . ':editAgent')->setName("edit-agent");
        $group->post('/agents/edit/{id}', AdminAction::class . ':updateAgent')->setName("update-agent");
        $group->post('/agents/delete/{id}', AdminAction::class . ':deleteAgent')->setName("delete-agent");
    })->add(IsAdminMiddleware::class);
};
