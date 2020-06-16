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
        //$newUser = false;
        $formData = $this->getFormData();
        $email = $formData->email;
        $email = strtolower(trim($email));
        
        $users = $this->userRepository->findUsersByEmail($email);

        if (count($users) > 0) {
            throw new Exceptions\BadRequestException('A user with this email already exists in this district.');
        }

        $payload = $this->authService->createUser($email, null, $this->token->dist);
        
        if ($payload->statusCode == 200) {
            //$newUser = true;
            $globalUserId = (int) $payload->global_user_id;
        }
        else { //303 - User already exists
            $globalUserId = (int) $payload->error->resource->id;
        }

        if (!is_numeric($globalUserId)) {
            throw new Exceptions\InternalServerErrorException('There was an issue setting up the user with authentication.');
        }

        $person = new Person();
        $person->setStatus(1);

        $name = $person->getName();
        $name->setGivenName($formData->firstName);
        $name->setFamilyName($formData->lastName);
        $person->setName($name);

        $email = new PersonEmail();
        $email->setEmailAddress($formData->email);
        $person->setEmail($email);

        // TA vulnerability
        // if user already exists in another district, they can invite that user to their district, modify their credenntials, and sign into other district
        // admins should not be allowed to update authentication credentials of any user. They can send a password reset only, or disable from within their district.

        $user = new User();
        $user->setPerson($person);
        $user->setLogin($formData->email);
        $user->setGlobalUserId($globalUserId);

        $this->userRepository->save($user);
        $this->authService->sendWelcomeEmail($user->getGlobalUserId(), $formData->firstName, $formData->lastName);

        /* if ($newUser) {
            $this->authService->sendWelcomeEmail($user->getId(), $formData->firstName, $formData->lastName);
        }
        else {
            $districtBuilding = $this->buildingRepository->findBuildingOfId((int) $this->token->building);
            $districtName = $$districtBuilding->getName();

            $sender = 'noreply@navigate360.com';
            $subject = "Visitor Management Invite";

            $plainText = ($formData->message ?? 'No message set.');
            $html =  "<p>Hi ". $formData->firstName . " " . $formData->lastName . "</p>" .
                      "You have been invited to "

            $messageId = $this->mailer->send($recipients, $sender, $subject, $html, $plainText);

            if (isset($formData->visitId)) {
                $visit = $this->visitRepository->findVisitOfId((int) $formData->visitId);
                $visit->setSecurityAlerted(true);
                $this->visitRepository->save($visit);
            }
        } */

        return $this->respondWithData(null, 201);
    }
}
