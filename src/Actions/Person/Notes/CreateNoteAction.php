<?php
declare(strict_types=1);

namespace App\Actions\Person\Notes;

use App\Actions\Person\PersonAction;
use App\Domain\Person\Note;
use Psr\Http\Message\ResponseInterface as Response;

class CreateNoteAction extends PersonAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $data = $this->getFormData();
    $personId = (int) $this->resolveArg("id");

    if (!isset($personId) || !isset($noteId) || !isset($data->userId) || !isset($data->note)) {
      return $this->respondWithData(null, 400);
    }

    $note = new Note();
    $note->personId = $personId;
    $note->userId = $data->userId;
    $note->note = $data->note;

    $person = $this->personRepository->findPersonOfId($personId);
    $note->setPerson($person);
    $person->addNote($note);

    $this->personRepository->save($person);

    $this->logger->info("Note Saved");

    return $this->respondWithData(null, 200);
  }
}
/**
 * @OA\POST(
 *     path="/persons/{personId}/notes",
 *     tags={"persons"},
 *     @OA\Response(
 *         response=201,
 *         description="Create Notes",
 *          @OA\MediaType(
      *             mediaType="application/json"
      *         )
 *     ),
 *     @OA\RequestBody( )
 * )
 */