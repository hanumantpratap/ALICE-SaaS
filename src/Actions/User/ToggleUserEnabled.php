<?php
declare(strict_types=1);

namespace App\Actions\User;

use BadRequestException;
use Psr\Http\Message\ResponseInterface as Response;

class ToggleUserEnabled extends UserAction
{
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);

        $formData = $this->getFormData();

        if (isset($formData->enabled)) {
            if ($formData->enabled) {
                $user->enable();
            }
            else {
                $user->disable();
            }
        }
        else {
            throw new BadRequestException();
        }

        $this->userRepository->save($user);

        $this->logger->info("User of id `${userId}` was " . ($formData->enabled ? "enabled" : "disabled") . ".");

        return $this->respondWithData($user);
    }
}
 /**
     * @OA\PUT(
     *     path="/users/{userId}/enabled",
     *     tags={"users"},
     *      @OA\Response(
     *         response=200,
     *         description="enable user"
     *     )
     * )
     */