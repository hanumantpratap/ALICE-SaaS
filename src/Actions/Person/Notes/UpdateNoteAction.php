<?php
declare(strict_types=1);

namespace App\Actions\Person\Notes;

use DateTime;
use App\Actions\Person\PersonAction;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateNoteAction extends PersonAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $data = $this->getFormData();
    $personId = (int) $this->resolveArg("id");
    $noteId = (int) $this->resolveArg("noteId");

    $person = $this->personRepository->findPersonOfId($personId);

    $note = $person->getNoteById($noteId);
    $note->userId = $data->userId;
    $note->note = $data->note;
    $note->updatedAt = new DateTime();

    $person->updateNote($note);

    $this->personRepository->save($person);

    $this->logger->info("Note Saved");

    return $this->response->withStatus(201);
  }
}
