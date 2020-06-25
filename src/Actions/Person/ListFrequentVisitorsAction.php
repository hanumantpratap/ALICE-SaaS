<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;

class ListFrequentVisitorsAction extends PersonAction
{
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();

        $limit = (int) ($params["limit"] ?? 10);
        $threshold = (int) ($params["threshold"] ?? 3);

        $buildingId = (int) $this->token->building;

        /**
         * @var $persons array
         */
        $persons = $this->personRepository->getFrequentVisitors($threshold, $limit, $buildingId);

        $this->logger->info("All persons retrieved.");

        return $this->respondWithData($persons);
    }

    /**
     * @OA\Get(
     *     path="/persons/frequent",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="View Frequent Persons",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200,
     *                      "data": {
     *                          {
     *                              "personId": 3460,
     *                              "status": 1,
     *                              "displayName": null,
     *                              "externalId": null,
     *                              "type": null,
     *                              "name": {
     *                                  "id": 3395,
     *                                  "personId": 3460,
     *                                  "nameType": 2,
     *                                  "givenName": "Chris",
     *                                  "middleName": null,
     *                                  "familyName": "Akers",
     *                                  "nickName": null,
     *                                  "suffix": null,
     *                                  "title": null
     *                              },
     *                              "demographics": null,
     *                              "email": null,
     *                              "address": null
     *                          },
     *                          {
     *                              "personId": 3461,
     *                              "status": 1,
     *                              "displayName": "McKellen, Sean",
     *                              "externalId": null,
     *                              "type": null,
     *                              "name": {
     *                                  "id": 3396,
     *                                  "personId": 3461,
     *                                  "nameType": 2,
     *                                  "givenName": "Sean",
     *                                  "middleName": null,
     *                                  "familyName": "McKellen",
     *                                  "nickName": null,
     *                                  "suffix": null,
     *                                  "title": null
     *                              },
     *                          },
     *                      }
     *                  }
     *         )
     *     )
     * )
     */
}
