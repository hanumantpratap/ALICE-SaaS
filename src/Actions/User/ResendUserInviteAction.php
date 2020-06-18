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

        return $this->respondWithData(null, 201);
    }
}
