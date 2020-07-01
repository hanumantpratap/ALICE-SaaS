<?php
declare(strict_types=1);

namespace App\Actions\Setup;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\User\UserRepository;
use App\Exceptions;

class AccountSetupAction extends SetupAction
{
    protected function action(): Response
    {
        $token =  $this->request->getAttribute('token');

        // Validate Invite Token
        if (!$token || $token->type != 'invite') {
            throw new Exceptions\UnauthorizedException();
        }
        
        $this->container->set('secureID', $token->dist);
        $this->userRepository = $this->container->get(UserRepository::class);

        $formData = $this->getFormData();

        $userId = (int) $this->token->id;
        $user = $this->userRepository->findUserOfId($userId);

        if ($user->getGlobalUserId() != $token->gid) {
            throw new Exceptions\InternalServerErrorException();
        }

        $this->authService->updatePassword($this->token->gid, $formData->password);

        $person = $user->getPerson();
        $name = $person->getName();
        $name->setGivenName($formData->firstName);
        $name->setFamilyName($formData->lastName);
        $person->setName($name);

        $this->userRepository->save($user);

        $authToken = [
            'id' => $user->getId(),
            'gid' => $user->getGlobalUserId(),
            'building' => $user->getPrimaryBuildingId(),
            'type' => 'auth',
            'dist' => $token->dist,
            'admin' => 'f'
        ];

        $newToken = $this->tokenProcessor->create((object) $authToken, 60*60*24, true);

        return $this->respondWithData(['token' => $newToken, 'tokenDecoded' => $authToken]);
    }

     /**
     * @OA\Post(
     *     path="/account/setup",
     *     tags={"account"},
     *     @OA\Response(
     *         response=200,
     *         description="Account setup",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     )
     * )
     */
}
