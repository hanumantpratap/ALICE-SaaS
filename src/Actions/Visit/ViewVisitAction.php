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
     *             example={"statusCode": 200, "data": {
     *                              "id": 10, 
    *                               "visitor_id": 12, 
     *                              "visitor_name": "Jessica Smith", 
     *                              "date_created": "2020-05-01 11:15:40",
     *                              "check_in": "2020-05-01 11:15:40",
     *                              "check_out": "2020-05-01 11:15:40",
     *                              "user_id": 3,
     *                              "user_name": "Mike Jones",
     *                              "notes": "Here are some notes."
     *                          }}
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
