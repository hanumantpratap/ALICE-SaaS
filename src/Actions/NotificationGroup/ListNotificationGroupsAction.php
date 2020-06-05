<?php
declare(strict_types=1);

namespace App\Actions\NotificationGroup;

use Psr\Http\Message\ResponseInterface as Response;

class ListNotificationGroupsAction extends NotificationGroupAction
{
    /**
     * @OA\GET(
     *     path="/notificationGroups",
     *     tags={"notificationGroups"},
     *      @OA\Response(
     *         response=200,
     *         description="List Notification Groups"
     *     )
     * )
     */
    protected function action(): Response
    {
        $groups = $this->notificationGroupRepository->findAll();

        $this->logger->info("Notification Groups list was viewed.");
        
        return $this->respondWithData($groups);
    }
}
