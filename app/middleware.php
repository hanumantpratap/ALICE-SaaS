<?php
declare(strict_types=1);

use App\Middleware\TokenMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(TokenMiddleware::class);
};
