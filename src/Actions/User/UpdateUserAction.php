<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Person\PersonPhone;
use App\Exceptions;

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

        if (isset($formData->phone)) {
            $phoneNumber = preg_replace('/[^\d]/i', '', $formData->phone);
            if(preg_match("/^[0-9]{3}[0-9]{4}[0-9]{4}$/", $phoneNumber)) {
                throw new Exceptions\BadRequestException('Invalid phone number format.');
            }

            $phone = $person->getPhoneByType(3);

            if ($phone === null) {
                $phone = new PersonPhone();
                $phone->setType(3);
                $person->addPhone($phone);
            }

            $phone->setPhoneNumber($phoneNumber);
        }

        if (isset($formData->enabled)) {
            if ($formData->enabled) {
                $user->enable();
            }
            else {
                $user->disable();
            }
        }

        if (isset($formData->role)) {
            $user->setRole($formData->role);
        }

        $this->userRepository->save($user);

        $this->logger->info("User of id `${userId}` was updated.");

        return $this->respondWithData(null, 200);
    }
}
/**
 * @OA\Put(
 *     path="/users/{userId}",
 *     tags={"users"},
 *     @OA\Response(
 *         response=200,
 *         description="Update User",
 *     )
 * )
 */