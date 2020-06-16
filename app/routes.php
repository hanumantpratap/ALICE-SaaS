<?php
declare(strict_types=1);

use App\Actions\Person\ListPersonsAction;
use App\Actions\Person\SearchPersonsAction;
use App\Actions\Person\ViewPersonAction;
use App\Actions\Student\ListStudentsAction;
use App\Actions\Student\SearchStudentsAction;
use App\Actions\Person\ViewVisitorSettingsAction;
use App\Actions\Person\SetVisitorSettingsAction;
use App\Actions\Person\AddStudentAction;
use App\Actions\Person\RemoveStudentAction;
use App\Actions\User\ListUsersAction;
use App\Actions\User\ViewUserAction;
use App\Actions\User\AddNotificationGroupAction;
use App\Actions\Visit\ListVisitsAction;
use App\Actions\Visit\ViewVisitAction;
use App\Actions\Visit\CreateVisitAction;
use App\Actions\Visit\ListVisitorTypesAction;
use App\Actions\Visit\ListVisitReasonsAction;
use App\Actions\User\SignInAction;
use App\Actions\User\ForgotPasswordAction;
use App\Actions\User\ResetPasswordAction;
use App\Actions\Visit\AddVisitBadgeAction;
use App\Actions\Visit\UpdateVisitAction;
use App\Actions\ID\IDScanAction;
use App\Actions\Person\AddBlacklistAction;
use App\Actions\Person\DeleteBlacklistAction;
use App\Actions\Person\ListBlacklistAction;
use App\Actions\Person\UpdateBlacklistAction;
use App\Actions\Person\Notes\GetNoteAction;
use App\Actions\Person\Notes\ListNotesAction;
use App\Actions\Person\Notes\CreateNoteAction;
use App\Actions\Person\Notes\UpdateNoteAction;
use App\Actions\NotificationGroup\ListNotificationGroupsAction;
use App\Actions\NotificationGroup\SendNotificationAction;
use App\Actions\Dev\Redis\{RedisSetAction, RedisGetAction, RedisListAction};
use App\Actions\Visit\ApproveVisitAction;
use App\Middleware\AuthMiddleware;
use Doctrine\ORM\Mapping\PreFlush;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {        
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/php-info', function (Request $request, Response $response) {        
        return phpinfo();
    });

    /* Routes that require signed in user */
    $app->group('', function (Group $group) {
        $group->group('/visits', function (Group $group) {
            $group->get('', ListVisitsAction::class);
            $group->get('/{id}', ViewVisitAction::class);
            $group->post('', CreateVisitAction::class);
            $group->put('/{id}', UpdateVisitAction::class);
            $group->post('/{id}/badge', AddVisitBadgeAction::class);  
            $group->put('/{id}/checkout', CheckOutAction::class);   
            $group->put('/{id}/approvevisit', ApproveVisitAction::class);       
        });
        $group->group('/visitortype', function (Group $group) {
            $group->get('', ListVisitorTypesAction::class);
        });
        $group->group('/visitreason', function (Group $group) {
            $group->get('', ListVisitReasonsAction::class);
        });

        $group->group('/users', function (Group $group) {
            $group->get('', ListUsersAction::class);
            $group->get('/{id}', ViewUserAction::class);
            $group->post('/{id}/notificationGroups', AddNotificationGroupAction::class);
        });
	
	    $group->group('/persons', function (Group $group) {
            $group->get('', ListPersonsAction::class);
            $group->get('/{id}', ViewPersonAction::class);
            $group->get('/search/query', SearchPersonsAction::class);
            $group->get('/{id}/blacklist', ListBlacklistAction::class);
            $group->post('/{id}/blacklist', AddBlacklistAction::class);
            $group->put('/{id}/blacklist/{blacklistId}', UpdateBlacklistAction::class);
            $group->get('/{id}/visitorSettings', ViewVisitorSettingsAction::class);
            $group->put('/{id}/visitorSettings', SetVisitorSettingsAction::class);
            $group->get('/{id}/notes', ListNotesAction::class);
            $group->get('/{id}/notes/{noteId}', GetNoteAction::class);
            $group->post('/{id}/notes', CreateNoteAction::class);
            $group->put('/{id}/notes/{noteId}', UpdateNoteAction::class);
            $group->post('/{id}/students', AddStudentAction::class);
            $group->delete('/{id}/students/{studentId}', RemoveStudentAction::class);
        });

        $group->group('/blacklist', function (Group $group) {
            $group->delete('/{id}', DeleteBlacklistAction::class);
        });

        $group->group('/students', function (Group $group) {
            $group->get('', ListStudentsAction::class);
            $group->get('/search/query', SearchStudentsAction::class);
        });

        $group->group('/notificationGroups', function (Group $group) {
            $group->get('', ListNotificationGroupsAction::class);
            $group->post('/{id}/notifications', SendNotificationAction::class);
        });
    
        $group->post('/id-scan', IDScanAction::class);
    })->add(AuthMiddleware::class);

    $app->group('/dev', function (Group $group) {
        $group->group('/redis', function (Group $group) {
            $group->get('', RedisListAction::class);
            $group->post('', RedisSetAction::class);
            $group->get('/{key}', RedisGetAction::class);
        });

        $group->get('/generate-docs', \App\Actions\Dev\GenerateOpenAPIDocs::class);
        $group->get('/docs', \App\Actions\Dev\ViewSwaggerAction::class);
    });

    $app->post('/sign-in', SignInAction::class);
    $app->post('/forgot-password', ForgotPasswordAction::class);
    $app->post('/reset-password', ResetPasswordAction::class);
};
