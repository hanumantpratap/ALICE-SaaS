<?php
declare(strict_types=1);

use App\Middleware\TokenMiddleware;
use App\Middleware\CorsMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(CorsMiddleware::class);
    $app->add(TokenMiddleware::class);
};
