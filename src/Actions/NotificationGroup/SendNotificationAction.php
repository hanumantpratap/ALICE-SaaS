<?php
declare(strict_types=1);

namespace App\Actions\NotificationGroup;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions;

class SendNotificationAction extends NotificationGroupAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        $notificationGroupId = (int) $this->resolveArg('id');
        $notificationGroup = $this->notificationGroupRepository->findNotificationGroupOfId($notificationGroupId);

        $users = $notificationGroup->getUsersByBuildingId((int) $this->token->building);

        if ($users->isEmpty()) {
            throw new Exceptions\NotFoundException('No users found in this notification group.');
        }

        $recipients = [];
        foreach($users as $user) {
            $email = $user->getPerson()->getEmail()->getEmailAddress();
            $recipients[] = $email;
        }

        $this->logger->info("Send Security Alert to:", $recipients);

        $user = $this->userRepository->findUserOfId((int) $this->token->id);

        $sender = 'noreply@navigate360.com';
        $subject = 'Security Alert From '. $user->getFirstName() . ' ' . $user->getLastName();

        $plainText = ($formData->message ?? 'No message set.');
        $html =  '<h1>Security Alert</h1>'.
                    '<p>'. ($formData->message ?? 'No message set.') . '</p>';

        $messageId = $this->mailer->send($recipients, $sender, $subject, $html, $plainText);

        if (isset($formData->visitId)) {
            $visit = $this->visitRepository->findVisitOfId((int) $formData->visitId);
            $visit->setSecurityAlerted(true);
            $this->visitRepository->save($visit);
        }

        return $this->respondWithData(null, 201);
    }
}

/**
 * @OA\POST(
 *     path="/notificationGroups/{notificationId}/notifications",
 *     tags={"notificationGroups"},
 *      @OA\Parameter(
 *         name="notificationId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Notification send",
 *     ),
 *     @OA\RequestBody( )
 * )
 */