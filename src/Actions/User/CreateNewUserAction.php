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

        $name = $person->getName();
        $name->setGivenName($formData->firstName);
        $name->setFamilyName($formData->lastName);
        $person->setName($name);

        $email = new PersonEmail();
        $email->setEmailAddress($formData->email);
        $person->setEmail($email);

        $user = new User();
        $user->setPerson($person);
        $user->setLogin($formData->email);
        $user->setGlobalUserId($globalUserId);

        $this->userRepository->save($user);
        $this->authService->sendWelcomeEmail($user->getGlobalUserId(), $formData->firstName, $formData->lastName);

        return $this->respondWithData(null, 201);
    }
}
