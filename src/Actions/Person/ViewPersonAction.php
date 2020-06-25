<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;

class ViewPersonAction extends PersonAction
{
    /**
     * {@inheritdoc}
     * OA\Get
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $person = $this->personRepository->findPersonOfId($userId);

        $this->logger->info("Person of id `${userId}` was viewed.");
        return $this->respondWithData($person);
    }
     /**
     * @OA\Get(
     *     path="/persons/{personId}",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="View Person",
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
 /**
     * @OA\Get(
     *     path="/persons/{personId}",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="View Person",
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