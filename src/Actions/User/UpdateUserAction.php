<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateUserAction extends UserAction
{
    protected function action(): Response
    {
        $formData = $this->getFormData();
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);
        
        $person = $user->getPerson();
        $name = $person->getName();

        if (isset($formData->firstName)) {
            $name->setGivenName($formData->firstName);
        }

        if (isset($formData->lastName)) {
            $name->setFamilyName($formData->lastName);
        }

        if (isset($formData->firstName) || isset($formData->lastName)) {
            $person->setName($name);
        }

        if (isset($formData->enabled)) {
            if ($formData->enabled) {
                $user->enable();
            }
            else {
                $user->disable();
            }
        }

        $this->userRepository->save($user);

        $this->logger->info("User of id `${userId}` was updated.");

        return $this->respondWithData(null, 200);
    }
}
