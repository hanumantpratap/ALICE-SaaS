<?php
declare(strict_types=1);

namespace App\Actions\Person;

use App\Domain\Person\BlacklistItem;
use Psr\Http\Message\ResponseInterface as Response;

class AddBlacklistAction extends PersonAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $data = $this->getFormData();
    $personId = (int) $this->resolveArg("id");

    $blItem = new BlacklistItem();
    $blItem->personId = $personId;
    $blItem->userId = $data->userId;
    $blItem->buildingId = $data->buildingId;
    $blItem->notes = $data->notes;
    $blItem->reason = $data->reason;

    $person = $this->personRepository->findPersonOfId($blItem->personId);
    $blItem->setPerson($person);
    $person->addBlacklistItem($blItem);

    $this->personRepository->save($person);

    $this->logger->info("Blacklist Item Saved.");

    return $this->response->withStatus(201);
  }
}
