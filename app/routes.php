<?php
declare(strict_types=1);

// if we need to scale:
//use some\namespace\{ClassA, ClassB, ClassC as C};

use App\Actions\Person\ListPersonsAction;
use App\Actions\Person\SearchPersonsAction;
use App\Actions\Person\ViewPersonAction;
use App\Actions\User\ListUsersAction;
use App\Actions\User\ViewUserAction;
use App\Actions\User\AddNotificationGroupAction;
use App\Actions\User\SignInAction;
use App\Actions\Visit\ListVisitsAction;
use App\Actions\Visit\ViewVisitAction;
use App\Actions\Visit\CreateVisitAction;
use App\Actions\ID\IDScanAction;
use App\Actions\Person\AddBlacklistAction;
use App\Actions\Person\ListBlacklistAction;
use App\Actions\PreflightAction;
use App\Middleware\AuthMiddleware;
use Doctrine\ORM\Mapping\PreFlush;
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

    /* Routes that require signed in user */
    $app->group('', function (Group $group) {
        $group->options('', PreflightAction::class);

        $group->group('/visits', function (Group $group) {
            $group->get('', ListVisitsAction::class);
            $group->get('/{id}', ViewVisitAction::class);
            $group->post('', CreateVisitAction::class);
        });

        $group->group('/users', function (Group $group) {
            $group->get('', ListUsersAction::class);
            $group->get('/{id}', ViewUserAction::class);
            $group->post('/{id}/notificationGroups', AddNotificationGroupAction::class);
        });
	
	    $group->group('/persons', function (Group $group) {
            $group->options('/search/query', PreflightAction::class);
            $group->get('', ListPersonsAction::class);
            $group->get('/{id}', ViewPersonAction::class);
            $group->get('/search/query', SearchPersonsAction::class);
            $group->get('/{id}/blacklist', ListBlacklistAction::class);
            $group->post('/{id}/blacklist', AddBlacklistAction::class);
        });
    
        $group->post('/id-scan', IDScanAction::class);
        $group->options('/id-scan', PreflightAction::class);
              
        $group->group('/dev', function (Group $group) {
            $group->group('/examples', function (Group $group) {
                $group->get('/database-fetch', \App\Actions\Dev\Examples\DatabaseFetchAction::class);
                $group->get('/database-fetchall', \App\Actions\Dev\Examples\DatabaseFetchAllAction::class);
                $group->get('/entity-fetchall', \App\Actions\Dev\Examples\EntityFetchAll::class);
            });

            $group->get('/generate-docs', \App\Actions\Dev\GenerateOpenAPIDocs::class);
            $group->get('/docs', \App\Actions\Dev\ViewSwaggerAction::class);
        });
    })->add(AuthMiddleware::class);

    
    $app->post('/sign-in', SignInAction::class);
    $app->options('/sign-in', PreflightAction::class);
};
