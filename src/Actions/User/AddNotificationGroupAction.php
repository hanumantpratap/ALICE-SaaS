<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Domain\User\UserRepository;
use App\Domain\NotificationGroup\NotificationGroupRepository;
use App\Exceptions;

class AddNotificationGroupAction extends UserAction
{
    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     * @param NotificationGroupRepository $notificationGroupRepository
     */

    public function __construct(LoggerInterface $logger, UserRepository $userRepository, NotificationGroupRepository $notificationGroupRepository)
    {
        parent::__construct($logger, $userRepository);
        $this->notificationGroupRepository = $notificationGroupRepository;
    }

    /**
     * @OA\POST(
     *     path="/users/{userId}/notificationGroups",
     *     tags={"users"},
     *      @OA\Response(
     *         response=200,
     *         description="Add Notification Group"
     *     )
     * )
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);

        $formData = $this->getFormData();

        if (!isset($formData->notificationGroupId)) {
            throw new Exceptions\BadRequestException('Please provide a notification group to add to the User.');
        }

        $notificationGroupId = (int) $formData->notificationGroupId;
        $this->logger->info("adding Notification Group `${notificationGroupId}` to User `${userId}`");

        $notificationGroup = $this->notificationGroupRepository->findnotificationGroupOfId($notificationGroupId);
        $notificationGroup->addUser($user);
        $this->notificationGroupRepository->save($notificationGroup);

        /* User Save has not been implemented yet. Special considerations need to be made for updating users.*/
        /* $user->addNotificationGroup($notificationGroup);
        $this->userRepository->save($user); */

        return $this->respondWithData($notificationGroup);
    }
}
