<?php
declare(strict_types=1);

namespace App\Actions\Person\Notes;

use App\Actions\Person\PersonAction;
use Psr\Http\Message\ResponseInterface as Response;

class ListNotesAction extends PersonAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $personId = (int) $this->resolveArg("id");

    $notes = $this->personRepository->findPersonOfId($personId)->getNotes();

    return $this->respondWithData($notes);
  }
  /**
     * @OA\Get(
     *     path="/persons/{personId}/notes",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="Notes List",
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
     *     path="/persons/{personId}/notes",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="Notes List",
     *  )
     * )
     */