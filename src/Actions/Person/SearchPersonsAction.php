<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;

class SearchPersonsAction extends PersonAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $params = $this->request->getQueryParams();
        $persons = $this->personRepository->findPersonsByParams($params);
 
        $this->logger->info("SearchPersonAction::action() retrieved the records." );

        return $this->respondWithData($persons);
    }
    
    /**
     * @OA\Get(
     *     path="/persons/search/query",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="Search Person",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"statusCode": 200,
     *                      "data": {
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
