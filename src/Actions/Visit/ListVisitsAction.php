<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class ListVisitsAction extends VisitAction
{
    protected function action(): Response
    {
        $visits = array();

        $visits = $this->visitsService->fetchAll();

        $this->logger->info("Visits list was viewed.");
        
        return $this->respondWithData($visits);
    }

    /**
     * @OA\Get(
     *     path="/visits",
     *     tags={"visits"},
     *     @OA\Parameter(
     *         name="visitor_name",
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
     *                              "personName": "Lauren Admin",
     *                              "dateCreated": "2020-05-01 15:15:40.638842+00",
     *                              "checkin": null,
     *                              "checkOut": null,
     *                              "userId": 200000037,
     *                              "userName": "Mae Admin",
     *                              "notes": "test"
     *                           },
     *                           {
     *                              "id": 3,
     *                              "personId": 3185,
     *                              "personName": "Rosalinda Walt",
     *                              "dateCreated": null,
     *                              "checkin": null,
     *                              "checkOut": null,
     *                              "userId": 200000037,
     *                              "userName": "Mae Walt",
     *                              "notes": "hello"
     *                           }
     *                      }}
     *         )
     *     )
     * )
     */
}
