<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class ViewVisitAction extends Action
{
    /**
     * @OA\Get(
     *     path="/visits/{visitId}",
     *     tags={"visits"},
     *     @OA\Parameter(
     *         name="visitId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="View Visitor",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200, "data": { "id": 1, "name": "Jessica Smith", "email": "jsmith@email.com"}}
     *         )
     *     )
     * )
     */
    protected function action(): Response
    {
        $visitId = (int) $this->resolveArg('id');

        $this->logger->info("Visit of id `${visitId}` was viewed.");

        return $this->respondWithData([]);
    }
}
