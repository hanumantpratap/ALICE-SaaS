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
