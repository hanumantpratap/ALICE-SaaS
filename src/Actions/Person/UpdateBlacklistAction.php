<?php
declare(strict_types=1);

namespace App\Actions\Person;

use DateTime;
use App\Domain\Person\BlacklistItem;
use App\Domain\Person\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpdateBlacklistAction extends PersonAction
{
  protected EntityManagerInterface $em;
  protected ObjectRepository $repo;

  public function __construct(EntityManagerInterface $em, LoggerInterface $logger, PersonRepository $repo)
  {
      parent::__construct($logger, $repo);
      $this->em = $em;
      $this->repo = $em->getRepository(BlacklistItem::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function action(): Response {
    $data = $this->getFormData();

    $blItem = $this->repo->findOneBy(["id" => $data->id]);

    $blItem->userId = $data->userId;
    $blItem->buildingId = $data->buildingId;
    $blItem->notes = $data->notes;
    $blItem->reason = $data->reason;
    $blItem->updatedAt = new DateTime();

    $this->em->flush();

    $this->logger->info("Blacklist item updated.");

    return $this->respondWithData($blItem);
  }
}
