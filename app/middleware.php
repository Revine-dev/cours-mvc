<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use App\Application\Middleware\HelperMiddleware;
use App\Application\Middleware\CsrfMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(CsrfMiddleware::class);
    $app->add(HelperMiddleware::class);
};
