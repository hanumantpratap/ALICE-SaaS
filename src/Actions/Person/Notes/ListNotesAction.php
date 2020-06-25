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