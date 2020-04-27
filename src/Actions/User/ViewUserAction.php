<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class ViewUserAction extends Action
{
    /**
     * @OA\Get(
     *     path="/users/{userId}",
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="View User",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200, "data": { "id": 1, "name": "Jessica Smith", "email": "jsmith@email.com"}}
     *         )
     *     )
     * )
    */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $this->logger->info("User of id `${userId}` was viewed.");

        return $this->respondWithData(['params' => 'value']);
    }
}
