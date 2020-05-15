<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class ViewVisitAction extends VisitAction
{
    protected function action(): Response
    {
        $visitId = (int) $this->resolveArg('id');
        $visit = $this->visitRepository->findVisitOfId($visitId);

        $this->logger->info("Visit of id `${visitId}` was viewed.");

        return $this->respondWithData($visit);
    }

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
     *             example={
     *                 "statusCode": 200, 
     *                 "data": {
     *                      "id": 10,
     *                      "personId": 3185,
     *                      "dateCreated": "2020-05-07 22:25:47.262826+00",
     *                      "reasonId": null,
     *                      "checkIn": null,
     *                      "checkOut": null,
     *                      "userId": 200000127,
     *                      "identificationId": null,
     *                      "notes": "hello",
     *                      "estimatedCheckIn": null,
     *                      "estimatedCheckOut": null,
     *                      "visitor": {
     *                          "personId": 3185,
     *                          "firstName": "Rosalinda",
     *                          "lastName": "Walt",
     *                          "emailAddress": "Rosalinda.Walt@laureninnovations.com"
     *                      }
     *                  }
     *              }
     *         )
     *     )
     * )
     */
}
