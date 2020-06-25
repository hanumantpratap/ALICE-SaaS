<?php
declare(strict_types=1);

namespace App\Actions\Person;

use App\Domain\Person\BlacklistItem;
use App\Domain\Person\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteBlacklistAction extends PersonAction
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
    $blItemId = (int) $this->resolveArg("id");

    $blItem = $this->repo->findOneBy(['id' => $blItemId]);

    if ($blItem == null) {
      $this->logger->info("Blacklist item not found");
      return $this->response->withStatus(404);
    }

    $this->em->remove($blItem);
    $this->em->flush();

    $this->logger->info("Blacklist item deleted.");

    return $this->response->withStatus(204);
  }

   /**
 * @OA\Delete(
 *     path="/blacklist/{blacklistId}",
 *     tags={"delete-blacklist"},
 *      @OA\Parameter(
 *         name="blacklistid",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="BlackList removed.",
 *     )
 * )
 */

}
