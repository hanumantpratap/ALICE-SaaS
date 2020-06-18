<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;

class UpdateUserNotificationGroups extends UserAction
{
    /**
     * @OA\PUT(
     *     path="/users/{userId}/notificationGroups",
     *     tags={"users"},
     *      @OA\Response(
     *         response=200,
     *         description="Update User Notification Groups"
     *     )
     * )
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);

        $formData = $this->getFormData();

        if (!isset($formData->notificationGroups) || !is_array($formData->notificationGroups)) {
            throw new Exceptions\BadRequestException();
        }

        $user->clearNotificationGroups();
        $this->userRepository->save($user);
        
        foreach ($formData->notificationGroups as $group) {
            $notificationGroup = $this->notificationGroupRepository->findnotificationGroupOfId((int) $group->notificationGroupId);
            $user->addNotificationGroup($notificationGroup, (int) $this->token->building, (bool) $group->email, (bool) $group->text);
        }

        $this->userRepository->save($user);

        return $this->respondWithData(null, 200);
    }
}
