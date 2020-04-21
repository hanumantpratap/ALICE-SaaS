<?php
declare(strict_types=1);

// if we need to scale:
//use some\namespace\{ClassA, ClassB, ClassC as C};

use App\Actions\User\ListUsersAction;
use App\Actions\User\ViewUserAction;
use App\Actions\Visit\ListVisitsAction;
use App\Actions\Visit\ViewVisitAction;
use App\Actions\Visit\CreateVisitAction;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {        
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/php-info', function (Request $request, Response $response) {        
        return phpinfo();
    });

    //$app->post('/sign-in', SignInAction::class);
    //$app->post('/district-select', DistrictSelectAction::class);

    $app->group('/visits', function (Group $group) {
        $group->get('', ListVisitsAction::class);
        $group->get('/{id}', ViewVisitAction::class);
        $group->post('', CreateVisitAction::class);
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->group('/dev', function (Group $group) {
        $group->get('/generate-docs', \App\Actions\Dev\GenerateOpenAPIDocs::class);
    });
};
