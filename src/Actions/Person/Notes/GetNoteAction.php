<?php
declare(strict_types=1);

namespace App\Actions\Person\Notes;

use App\Actions\Person\PersonAction;
use Psr\Http\Message\ResponseInterface as Response;

class GetNoteAction extends PersonAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $personId = (int) $this->resolveArg("id");
    $noteId = (int) $this->resolveArg("noteId");

    $note = $this->personRepository->findPersonOfId($personId)->getNoteById($noteId);

    return $this->respondWithData($note);
  }
  /**
     * @OA\Get(
     *     path="/persons/{personId}/notes/{noteId}",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="View Note",
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
     *     path="/persons/{personId}/notes/{noteId}",
     *     tags={"persons"},
     *      @OA\Response(
     *         response=200,
     *         description="View Note",
     *     )
     * )
     */