<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\User\User;
use App\Domain\Person\Person;
use App\Domain\Person\PersonEmail;

class CreateNewUserAction extends UserAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        $email = $formData->email;
        
        $person = new Person();
        $person->setStatus(1);

        $name = $person->getName();
        $name->setGivenName($formData->firstName ?? 'Test');
        $name->setFamilyName($formData->lastName ?? 'Human');

        $email = new PersonEmail();
        $email->setEmailAddress($formData->email);
        $person->setEmail($email);

        // TA vulnerability
        // if user already exists in another district, they can invite that user to their district, modify their credenntials, and sign into other district
        // admins should not be allowed to update authentication credentials of any user. They can send a password reset only, or disable from within their district.

        $user = new User();
        $user->setPerson($person);
        $user->setLogin($formData->email);

        $this->userRepository->save($user);

        return $this->respondWithData(null, 201);
    }
}
