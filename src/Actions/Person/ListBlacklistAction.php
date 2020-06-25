<?php
declare(strict_types=1);

namespace App\Actions\Person;

use Psr\Http\Message\ResponseInterface as Response;

class ListBlacklistAction extends PersonAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $personId = (int) $this->resolveArg("id");

    $person = $this->personRepository->findPersonOfId($personId);

    $this->logger->info("Blacklist retrieved.");

    return $this->respondWithData($person->getBlacklist()->toArray());
  }
}
 /**
     * @OA\Get(
     *     path="/persons/{personId}/blacklist",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="Blacklist Person",
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