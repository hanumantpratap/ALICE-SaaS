<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends UserAction
{
    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"users"},
     *      @OA\Response(
     *         response=200,
     *         description="List Users"
     *     )
     * )
     */
    protected function action(): Response
    {
        $users = $this->userRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }
}
