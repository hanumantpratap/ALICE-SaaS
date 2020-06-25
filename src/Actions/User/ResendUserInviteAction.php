<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ResendUserInviteAction extends UserAction
{
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);

        $email = $user->getPerson()->getEmail()->getEmailAddress();
        $token = [
            'id' => $user->getId(),
            'gid' => $user->getGlobalUserId(),
            'dist' => $this->token->dist,
            'email' => $email,
            'type' => 'invite'
        ];

        $token = $this->tokenProcessor->create((object) $token, 60*10);
        $clientUrl = $this->container->get('settings')['clientUrl'];

        $sender = 'Visitor Management <noreply@navigate360.com>';
        $subject = 'Welcome to Visitor Management';

        $html = "<html lang='en-US'>
                    <head>
                        <title>Welcome to Visitor Management</title>
                    </head>
                    <body>
                        <p>Greetings!</p>
                        <p>Welcome to Visitor Management. Please visit the following link to setup your account:</p>
                        <p><a href='${clientUrl}/welcome/${token}' target='_BLANK'>Setup Account</a></p>
                        <p>Thank you</p>
                        <hr>
                        <img title='${subject}' alt='${subject} - Logo' src='images/360-logo-2.png' width='192' />
                    </body>
                </html>";

        $messageId = $this->mailer->send([$email], $sender, $subject, $html);

        return $this->respondWithData(['message' => 'Invite sent', 'messageId' => $messageId], 201);
    }
}
/**
     * @OA\POST(
     *     path="/users/{userId}/resend-invite",
     *     tags={"users"},
     *      @OA\Response(
     *         response=200,
     *         description="Resend invite"
     *     )
     * )
     */