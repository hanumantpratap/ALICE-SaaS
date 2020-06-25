<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\User\User;
use App\Domain\Person\Person;
use App\Domain\Person\PersonEmail;
use App\Exceptions;

class CreateNewUserAction extends UserAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        $email = $formData->email;
        $email = strtolower(trim($email));

        $users = $this->userRepository->findUsersByEmail($email);

        if (count($users) > 0) {
            throw new Exceptions\BadRequestException('A user with this email already exists in this district.');
        }

        $globalUserId = $this->authService->createUser($email, null, $this->token->dist);

        if (!is_numeric($globalUserId)) {
            throw new Exceptions\InternalServerErrorException('There was an issue setting up the user with authentication.');
        }

        $person = new Person();
        $person->setStatus(1);


        $email = new PersonEmail();
        $email->setEmailAddress($formData->email);
        $person->setEmail($email);

        $user = new User();
        $user->setPerson($person);
        $user->setLogin($formData->email);
        $user->setGlobalUserId($globalUserId);
        $user->setPrimaryBuildingId($this->token->building);

        $this->userRepository->save($user);

        $token = [
            'id' => $user->getId(),
            'gid' => $globalUserId,
            'dist' => $this->token->dist,
            'email' => $formData->email,
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

        $messageId = $this->mailer->send([$formData->email], $sender, $subject, $html);

        return $this->respondWithData(['message' => 'Invite sent', 'messageId' => $messageId], 201);
    }
}


/**
     * @OA\POST(
     *     path="/users",
     *     tags={"users"},
     *      @OA\Response(
     *         response=200,
     *         description="create new user"
     *     )
     * )
     */
