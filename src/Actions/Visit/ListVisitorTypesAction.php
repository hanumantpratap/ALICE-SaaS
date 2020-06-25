<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class ListVisitorTypesAction extends VisitorTypeAction
{
    protected function action(): Response
    {
        $visitorTypes = $this->visitorTypeRepository->findAll();

        $this->logger->info("Visitor Type list was viewed.");
        
        return $this->respondWithData($visitorTypes);
    }

    /**
     * @OA\Get(
     *     path="/visitortype",
     *     tags={"visitortype"},
     *     @OA\Parameter(
     *         name="visitor_type",
     *         in="query",
     *         description="Filter by visitor",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="View Visits",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200,
     *                      "data": {
     *                          {
     *                              "id": 2,
     *                              "personId": 1,
     *                              "dateCreated": "2020-05-01 15:15:40.638842+00",
     *                              "reasonId": null,
     *                              "checkIn": null,
     *                              "checkOut": null,
     *                              "userId": 200000037,
     *                              "identificationId": null,
     *                              "notes": "test",
     *                              "estimatedCheckIn": null,
     *                              "estimatedCheckOut": null,
     *                              "visitor": {
     *                                  "personId": 1,
     *                                  "firstName": null,
     *                                  "lastName": null,
     *                                  "emailAddress": "support@laureninnovations.com"
     *                              }
     *                          },
     *                          {
     *                              "id": 10,
     *                              "personId": 3185,
     *                              "dateCreated": "2020-05-07 22:25:47.262826+00",
     *                              "reasonId": null,
     *                              "checkIn": null,
     *                              "checkOut": null,
     *                              "userId": 200000127,
     *                              "identificationId": null,
     *                              "notes": "hello",
     *                             "estimatedCheckIn": null,
     *                              "estimatedCheckOut": null,
     *                              "visitor": {
     *                                  "personId": 3185,
     *                                  "firstName": null,
     *                                  "lastName": null,
     *                                  "emailAddress": "Rosalinda.Walt@laureninnovations.com"
     *                              }
     *                          }
     *                      }
     *                  }
     *         )
     *     )
     * )
     */
}