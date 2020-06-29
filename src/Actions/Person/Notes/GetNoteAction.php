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